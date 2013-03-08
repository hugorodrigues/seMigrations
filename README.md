# seMigrations
Dead simple database migration utility for PDO (Mysql, pgSQL, sqLite).

* Simple usage - Use the CLI or programmatically in your App
* Decoupled - No dependencies and Less than 130 Lines of code
* Straightforward - No abstraction or incomplete DSL: Use raw sql (like a real man!)

<img src="http://i.imgur.com/GRAmGtg.png" width="650" border = "0"/>

---


## Usage
You and your team create new files and name them with the following syntax (ignore the spaces):
`YEAR MONTH DAY HOUR MINUTE - COMMENTS .sql`

Examples:
- `201212101233-Added_indexes.sql`
- `201212101255-Added_new_table.sql`


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
You can use multiple querys in one migration, just like a regular dump.

---



## Using the built-in interface

There are two main options:

### Download, edit and use

Edit the ```seMigrations``` script and set your configuration:
```php
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
$ ./seMigrations
```


### Full installation

The best way to use this script is puting your config in a separate file and pass the argument --conf=/path/toConf to the command. (In this way you can use seMigration in multiple projects using a unique and upgradable version )

Chose a path like /etc/seMigrationConf or /your/web/framework/conf/seMigrationConf to put your config:
```php
$seMigrationsConfig = array(
  'migrationsFolder' => './demoMigrations/',				// The folder for the migrations directory
  'db' => array(
		'dsn' => 'mysql:host=localhost;dbname=yourDb', 	// The pdo dsn for your database
		'user' => 'yourUserName', 											// Db username (if required)
		'password' => 'yourPassword'										// Db password (if required)
  )
);
```

Test it
```bash
$ ./seMigrations --config=/etc/seMigrationConf
```

Of course you can create a alias command and put in your path for usability:
```bash
$ echo "/full/path/to/seMigrations --config=/etc/seMigrationConf $@" > /bin/seMigrations
$ chmod +x /bin/seMigrations
```

Now you run from anywhere in your system:
```bash
$ seMigrations
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

