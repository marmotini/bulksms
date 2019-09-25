# Mysql 

### Setup
1. Install mysql database
2. Create ``bulksms`` database.
3. Install php mysql library ``sudo apt-get install php-mysqli``
4. Create message table 
```sql
create table message (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	`msg` TEXT NOT NULL, 
	`recipients` TEXT NOT NULL, 
	`from` VARCHAR(255),
	`status` VARCHAR(100), 
	`unique_chunk_identifier` VARCHAR(255),
	`order` INT(11)
);
```
