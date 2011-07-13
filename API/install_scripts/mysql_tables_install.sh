#!/bin/bash

db_dbname="testfluxAPI"
#source EDITME_config
echo "Insert the mysql username to install the tables (root usually):"
read db_username
echo "Insert the mysql password:"
read db_password

echo "====BEGIN TABLES INSTALLATION====="
echo "* Creating database '$db_dbname'"

#destroy and then create the database:
mysql -u $db_username -p"$db_password" -e "DROP DATABASE IF EXISTS $db_dbname;CREATE DATABASE $db_dbname;"

#
# TABLE users::::::::::::
#
echo "* Creating TABLE: users"
query="CREATE TABLE users(
user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
email VARCHAR(32),
password VARCHAR(32),
cellphone VARCHAR(32),
confirmed BOOL DEFAULT 0,
PRIMARY KEY (user_id)
) ENGINE = InnoDB;"
mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"

#
# TABLE fluxes:::::::::::
#
echo "* Creating TABLE: fluxes"
query="CREATE TABLE fluxes(
flux_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
name VARCHAR(32),
owner INT UNSIGNED NOT NULL,
description TEXT(100),
money DECIMAL(7,2)  NOT NULL DEFAULT 0,
PRIMARY KEY (flux_id)
) ENGINE = InnoDB;"
mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"

echo "* Populating    : fluxes"
#randomly populate table 'routes'
query="INSERT INTO fluxes (name, description, owner) VALUES
('flux1','description1', 1), ('flux2','description of this flux',1), ('flux 3', 'a flux owned by user 2',2), ('flux 4','another flux owned by u2',2);
"
mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"

#
# TABLE routes:::::::::::
#

echo "* Creating TABLE: routes"
#create the table with the 'routes'
query="CREATE TABLE routing(
flux_from_id INT UNSIGNED NOT NULL,
flux_to_id INT UNSIGNED NOT NULL,
share INT UNSIGNED NOT NULL DEFAULT 0,
INDEX (flux_from_id),
INDEX (flux_to_id),
PRIMARY KEY (flux_from_id,flux_to_id)
) ENGINE = InnoDB;"
mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"

echo "* Populating    : routes"
#randomly populate table 'routes'
query="INSERT INTO routing VALUES
(1,1,10), (2,2,20), (3,3,30), (1,2,30);
"
mysql -u $db_username -p"$db_password" -e "use $db_dbname;$query"


echo "======INSTALLATION COMPLETE!======"
