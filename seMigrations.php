<?php
//     seMigrations.php
//     http://starteffect.com | https://github.com/hugorodrigues
//     (c) Copyright 2010-2013 Hugo Rodrigues, StartEffect U. Lda
//     This code may be freely distributed under the MIT license.

class seMigrations {
  var $pdo;
  var $config = array('migrationsFolder' => './migrations/', 'ctrlTable' => '_seDatabaseMigrations');

  function __construct($config){
    $this->config = $config;

    try {
      $this->pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['password']);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $error) {
        die("ERROR: Invalid Database configuration. \n");
    }

    // Create ctrl table if not present
    if (!is_array($this->dbGetVar('show tables like "'.$this->config['ctrlTable'].'"')))
      $this->dbQuery('CREATE TABLE IF NOT EXISTS `'.$this->config['ctrlTable'].'` (`id` int(11) NOT NULL AUTO_INCREMENT, `migrations_folder` varchar(255) NOT NULL, `actual_migration` bigint(20) NOT NULL, `last_update` datetime NOT NULL, PRIMARY KEY (`id`) ) AUTO_INCREMENT=1;');

    // Status for this migration folder?
    if (null == $this->dbGetVar('select id from '.$this->config['ctrlTable'].' where migrations_folder = :migrations_folder',array(':migrations_folder'=>$this->config['migrationsFolder'])))
      $this->dbQuery('insert into '.$this->config['ctrlTable'].' (actual_migration, migrations_folder) values (:actual_migration, :migrations_folder)',array('actual_migration'=> 0, 'migrations_folder'=> $this->config['migrationsFolder']));

  }

  public function migrate($to = 'latest'){

    $status = $this->getMigrationStatus();

    if ($to === 'latest')
      $to = $status['lastMigration'];

    if ($to > 0 && !is_array($status['migrations'][$to]))
      return false;

    if ($to == $status['currentMigration'])
      return true;

    if ($status['currentMigration'] < $to)
      $x = $this->upTo($to, $status);
    else
      $x = $this->downTo($to, $status);

    // Set new migration
    $this->dbQuery('update '.$this->config['ctrlTable'].' set actual_migration = :actual_migration where migrations_folder = :folder',array('actual_migration'=> $to, ':folder'=>$this->config['migrationsFolder']));

    return $x;
  }

  public function getMigrationStatus(){

    $migrations = (array)@scandir($this->config['migrationsFolder']);
    $result = array();

    if ($migrations) 
      foreach ($migrations as $filename)
        if (substr($filename, -4) == '.sql')
        {
          $fileContents = file_get_contents($this->config['migrationsFolder'].'/'.$filename);
          $fileContents = explode('-- <<<<< SE MIGRATION >>>>> --', $fileContents);

          $result[substr($filename, 0, 12)] = array(
            'comment' => substr($filename, 13, -4),
            'filename' => $filename,
            'sqlUp' => trim($fileContents[0]),
            'sqlDown' => trim($fileContents[1])
          );
        }

    $result = array(
      'migrations' => $result,
      'lastMigration' => end(array_keys($result)),      
      'currentMigration' => $this->dbGetVar('select actual_migration from '.$this->config['ctrlTable'].' where migrations_folder = :folder', array(':folder' => $this->config['migrationsFolder'])),
    );

    return $result;
  }


  private function dbGetVar($sql='',$params=array())
  {
    try {
      $sth = $this->pdo->prepare($sql);
      $sth->execute($params); 
      $result = $sth->fetch(PDO::FETCH_NUM);
      $sth->closeCursor();
      return $result[0];
    }
    catch(Exception $exception)
    {
      return die($exception);
    }
  }

  private function dbQuery($sql, $params=array(), $status= false){
    try {
      $tmp = $this->pdo->prepare($sql);
      $tmp->execute($params); 
      $tmp->closeCursor();
    }
    catch(Exception $exception)
    {
      return die($exception);
    }

    return ($status == false) ?  $tmp : $result;
  }

  private function upTo($to, $status){

    $result = array();
    foreach ($status['migrations'] as $stamp => $migration)
      if ($stamp > $status['currentMigration'] && $stamp <= $to)
        $result[] = array($stamp, $migration, $this->dbQuery($migration['sqlUp']));

    return $result;
  }

  private function downTo($to, $status){

    $status['migrations'] = array_reverse($status['migrations'],true);

    $result = array();
    foreach ($status['migrations'] as $stamp => $migration)
      if ($stamp <= $status['currentMigration'] && $stamp > $to)
        $result[] = array($stamp, $migration, $this->dbQuery($migration['sqlDown']));

    return $result;
  }

}