#!/bin/bash
/usr/bin/mysqld_safe &
sleep 5
mysql -u root -e "CREATE DATABASE phpminds"
mysql -u root phpminds < /docker-entrypoint-initdb.d/init_db.sql