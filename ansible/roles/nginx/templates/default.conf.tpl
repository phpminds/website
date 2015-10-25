upstream backend {
        server 127.0.0.1:9000;
}

server {

	listen 80 default;

	root /srv/{{ web_server.app_web_dir }};

	index index.php;

	access_log /var/log/nginx/{{web_server.server_name}}-access.log;

	error_log /var/log/nginx/{{web_server.server_name}}-error.log;

	server_name {{ web_server.server_name }};

	location / {
		try_files $uri $uri/ /index.php;
	}

	# This location block matches anything ending in .php and sends it to
	# our PHP-FPM socket, defined in the upstream block above.
	location ~ \.php$ {

		try_files $uri =404;

		fastcgi_pass backend;

		fastcgi_index index.php;

		fastcgi_param SCRIPT_FILENAME /srv/{{ web_server.app_web_dir }}$fastcgi_script_name;

		include fastcgi_params;

	}

	# This location block is used to view PHP-FPM stats
	location ~ ^/(php_status|php_ping)$ {

		fastcgi_pass backend;

		fastcgi_param SCRIPT_FILENAME $fastcgi_script_name;

		include fastcgi_params;

		allow 127.0.0.1;

		deny all;

	}

	# This location block is used to view nginx stats
	location /nginx_status {

		stub_status on;

		access_log off;

		allow 127.0.0.1;

		deny all;

	}

}