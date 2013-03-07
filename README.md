# seMigrations
Dead simple database migration utility.




---

## Concept
Use raw sql files for your database


## Migrations folder
You and your team create new files and name them with the following syntax (ignore the spaces):
YEAR MONTH DAY HOUR MINUTE - COMMENTS .sql

Examples:
201212101233-Added_indexes.sql
201212101255-Added_new_table.sql


## Defining migrations

Now when you need to change something in you database you create a new file with your new changes and instructions on howto rollback. Use the following syntax:

```sql
The code that will DO something

-- <<<<< SE MIGRATION >>>>> --

The code that will UNDO
```

Check this real example:

```sql
CREATE TABLE IF NOT EXISTS `example1` (`column` VARCHAR(32) NOT NULL );

-- <<<<< SE MIGRATION >>>>> --

DROP TABLE `example1`;
```

If you run this migration it will create the table example1, if you rollback it will delete it.






## Define
```php
$db = new seDatabase(array(
	'dsn' => 'mysql:host=localhost;dbname=test',
	'user' => 'root',
	'password' => 'l33tpassword',
	//'options' => array(),
	//'attributes' => array(),
));
```





### migrate($to)
```php
$insertId = $db->insert('books',array(
	'title'=>'Cool book',
	'isbn'=>'AB123',
	'year'=>2012,
));

//Executes: insert into books (title, isbn) values ('Cool book', 'AB123');
```







---
## License 

(The MIT License)

Copyright (c) 2012, Hugo Rodrigues <hugo a@t starteffect.com>

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









