# vi /etc/php5/fpm/pool.d/default_app.conf

[default_app]

listen = /srv/tmp/php.sock

user = {{ web_server.server_user }}

group ={{ web_server.server_user_group }}

pm = dynamic

 pm.max_children = 100

 pm.start_servers = 10

 pm.min_spare_servers = 5

 pm.max_spare_servers = 15

 pm.max_requests = 1000

 pm.status_path = /php_status

 request_terminate_timeout = 0

 request_slowlog_timeout = 0

 slowlog = /srv/logs/slow.log