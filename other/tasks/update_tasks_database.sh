#!/bin/bash

db_dbname="fluxTasks"
#source EDITME_config
echo "Insert the mysql username to install the tables (root usually):"
read db_username
echo "Insert the mysql password:"
read db_password

echo "============ INSTALLATION============="
echo "* Creating database '$db_dbname'"

#destroy and then create the database:
mysql -u $db_username -p"$db_password" -e "DROP DATABASE IF EXISTS $db_dbname;CREATE DATABASE $db_dbname;"

#
# TABLE tasks::::::::::::
#
echo "* Creating TABLE: tasks"
query="CREATE TABLE tasks(
task_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
description TEXT NOT NULL,
date DATE NOT NULL,
PRIMARY KEY (task_id)
) ENGINE = MyISAM;"

mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"

#
# Populate table tasks:
# (tasks are put directly in the sql file)
#
mysql -u $db_username -p"$db_password" < tasks.sql
