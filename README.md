# seMigrations
Dead simple database migration utility for PDO (Mysql, pgSQL, sqLite).

* Simple usage - Use the CLI or programmatically in your App
* Decoupled - No dependencies and Less than 130 Lines of code
* Straightforward - No magic method or incomplete DSL for you. Use raw sql (like a real man)

<img src="http://i.imgur.com/GRAmGtg.png" width="650" border = "0"/>


---


## Usage
You and your team create new files and name them with the following syntax (ignore the spaces):
`YEAR MONTH DAY HOUR MINUTE - COMMENTS .sql`

Examples:
- 201212101233-Added_indexes.sql
- 201212101255-Added_new_table.sql


---


## Defining migrations

Now when you need to change something in you database you create a new file with your changes and rollback instructions. Use the following syntax:

```sql
The code that will DO something

-- <<<<< SE MIGRATION >>>>> --

The code that will UNDO
```

Example:

```sql
CREATE TABLE IF NOT EXISTS `example1` (`column` VARCHAR(32) NOT NULL );

-- <<<<< SE MIGRATION >>>>> --

DROP TABLE `example1`;
```

If you run this migration it will create the table example1, if you rollback it will delete it.


---



## Usage
Edit the ```seMigrations``` script and set your configuration:
```sql
$config = array(
  'ctrlTable' => '_seDatabaseMigrations',						// A table for control. Will be auto created
  'migrationsFolder' => './demoMigrations/',				// The folder for the migrations directory
  'db' => array(
		'dsn' => 'mysql:host=localhost;dbname=yourDb', 	// The pdo dsn for your database
		'user' => 'yourUserName', 											// Db username (if required)
		'password' => 'yourPassword'										// Db password (if required)
  )
);
```

After simples run it:
```bash
./seMigrations
```




### Initialize
Before you start you need a instance of seDatabase already connected to your database and define a control table (that will get created automatically) and the directory of your migration files
```php
$migrations = new seDatabaseMigrate($db, array(
      'ctrlTable' => '_seDatabaseMigrations',
      'migrationsFolder' => '/Volumes/Work/sites/framework2013/seDatabaseMigrate/demo/migrations/2/',
));
```




## Using programmatically
You should read the `seMigrations` code, but basically all you need is getMigrationStatus() and  migrate()

### migrate($to)

Go to a specific migration, it don't matter if it will go UP or DOWN:
```php
$migrations->migrate('201212101233');
```

Get your db with the latest available migration (sync):
```php
$migrations->migrate('latest');
```

Rollback all your migrations:
```php
$migrations->migrate(0);
```








---
## License 

(The MIT License)

Copyright (c) 2010-2013 Hugo Rodrigues, Hugo Rodrigues, StartEffect U. Lda
http://starteffect.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

