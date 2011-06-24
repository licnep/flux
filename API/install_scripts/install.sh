#!/bin/bash

#clear the "testAPI" database and create the tables:
./mysql_tables_install.sh

echo "changing the mysql username and password in the php script:"
source EDITME_config
sed -i "s/insertusername/$db_username/g" ../execute_query.php
sed -i "s/insertpassword/$db_password/g" ../execute_query.php
