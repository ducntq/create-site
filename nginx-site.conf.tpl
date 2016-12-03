upstream php_{domain} {
        server    unix:/dev/shm/php_{domain}.sock;
}


server {
    listen 80;
    server_name  www.{domain};
    rewrite ^(.*) http://{domain}$1 permanent;
}

server {
        listen 80;
        server_name {domain};
        root /home/{user}/{domain}/public_html;
        access_log /home/{user}/{domain}/logs/access.log;
        error_log /home/{user}/{domain}/logs/error.log;
	client_max_body_size 20M;

        location ~ /\.ht {
                deny all;
                access_log off;
                error_log off;
        }


        location = /favicon.ico {
                log_not_found off;
                access_log off;
                error_log off;
        }

        location = /robots.txt {
                allow all;
                log_not_found off;
                access_log off;
                error_log off;
        }

        location  / {
                index  index.php index.html index.htm;
                try_files $uri $uri/ /index.php?$args;
        }


        location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {
                expires max;
                gzip on;
                log_not_found off;
                access_log off;
                error_log off;
        }

        location ~ \.php$ {
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                include fastcgi_params;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                fastcgi_connect_timeout 50;
                fastcgi_send_timeout 180;
                fastcgi_read_timeout 180;
                fastcgi_buffer_size 128k;
                fastcgi_buffers 4 256k;
                fastcgi_busy_buffers_size 256k;
                fastcgi_temp_file_write_size 256k;
                fastcgi_intercept_errors on;
                fastcgi_pass php_{domain};
        }
}
