#!/bin/bash

text="
##########################################
#############API INSTALLATION#############
##########################################
#                                        #
#  This will install some tables in your #
#  mysql database, and change the mysql  #
#  username and password in the php.     #
#                                        #
#  You need php and mysql installed, and #
#  you must know your mysql username and #
#  password.                             #
#                                        #
#  If some of this is missing, interrupt #
#  the installation now, with Ctrl+C.    #
#                                        #
#  Have a nice day.                      #
#                                        #
##########################################
"

echo "$text"

#clear the "testAPI" database and create the tables:
source mysql_tables_install.sh

echo "changing mysql username and password in the php script"

# substitute $username="anything" with $username="given_username" in execute_query.php
sed -i "s/\$username=\"[^\"]*\"/\$username=\"$db_username\"/g" ../execute_query.php
sed -i "s/\$password=\"[^\"]*\"/\$password=\"$db_password\"/g" ../execute_query.php

echo "INSTALLATION COMPLETE!"
