#!/bin/bash

#mysql -u root --protocol=tcp --password=Admin123 -e "CREATE DATABASE phpminds"
mysql -u root --protocol=tcp --password=Admin123 phpminds < /docker-entrypoint-initdb.d/init_db.sql