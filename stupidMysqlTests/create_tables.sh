#!/bin/bash

dbname="testdb" #<-- this DB is created and deleted every time, change the name if you whish
username="root" 
password="password"
max=1 #max number of fluxes to be 'moved' every time, 1 is good to understand what happens

#destroy and then create the database:
mysql -u $username -p"$password" -e "drop database if exists $dbname;create database $dbname;"

#create the table with the fluxes:
query="CREATE TABLE fluxes(
flux_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(64),
owner_id INT UNSIGNED NOT NULL,
money_waiting INT UNSIGNED NOT NULL DEFAULT 0,
last_update TIMESTAMP NOT NULL DEFAULT NOW(),
PRIMARY KEY (flux_id),
INDEX (last_update)
);"

mysql -u $username -p"$password" -e "use $dbname;$query"

#create the table with the 'routes'
query="CREATE TABLE routing(
flux_from_id INT UNSIGNED NOT NULL,
flux_to_id INT UNSIGNED NOT NULL,
share INT UNSIGNED NOT NULL DEFAULT 0,
INDEX (flux_from_id),
INDEX (flux_to_id),
PRIMARY KEY (flux_from_id,flux_to_id)
);"

mysql -u $username -p"$password" -e "use $dbname;$query"


#now i compile this two c files, and use them to generate the rows to insert in the tables.
#if you want to test inserting a different number of rows or different caracteristics modify
#sql.c and sql2.c

#compile the file, launch the executable and save the outputted sql command in a .sql file
gcc sql2.c -o sql2.exe && ./sql2.exe > sql2.sql
gcc sql.c -o sql.exe && ./sql.exe > sql.sql

#load the .sql in the database:
mysql -u $username -p"$password" $dbname < sql.sql
mysql -u $username -p"$password" $dbname < sql2.sql

#load my procedure (the function used to update the money amounts) in the database:
mysql -u $username -p"$password" $dbname < sqlproc.sql

#loop infinite times:
while :
do

#i launch my function to 'move' the money: (limit the moving to only 1 row at a time, you can try 100 or more...)
mysql -u $username -p"$password" -e "use $dbname;CALL update_least_updated($max);"
#show the updated fluxes (only 10 rows):
mysql -u $username -p"$password" -e "use $dbname;select * from fluxes order by flux_id limit 10;"

sleep 1s
done
