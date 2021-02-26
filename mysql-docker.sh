# Backup
docker exec kenshu-php_mysql_1 /usr/bin/mysqldump -u root --password=root prt_db > database/prt_db.sql

# Restore
cat database/prt_db.sql | docker exec -i kenshu-php_mysql_1 /usr/bin/mysql -u root -p --password=root prt_db
