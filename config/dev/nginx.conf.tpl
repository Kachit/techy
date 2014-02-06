server {
    listen 80;

    server_name {{host}};
    charset utf-8;

    access_log {{root}}logs/{{app_name}}/nginx.access.log;
    error_log  {{root}}logs/{{app_name}}/nginx.error.log;

#prod
    location /compiled/ {
        root {{root}}static;
    }
    location /js/ {
        root {{root}}static;

        location /js/assets/ {
            root {{root}}static;
        }

        location /js/i18n/ {
            root {{root}}static/compiled/{{app_name}};
        }
    }
    location ~* \.(gif|png|jpg|jpeg|ico|txt|pdf|eot|svg|ttf|woff)$ {
        root {{root}}static;
    }

#dev
    location /assets/ {
        root {{root}}app/Application/{{app_name}}/views;
    }

    location /css/ {
        root {{root}}static/assets;
    }

    location / {
        root {{root}}dev;
        index index.php;
        rewrite ^/(.*)$ /index.php?q=$1 last;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/tmp/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_read_timeout 600s;
        fastcgi_send_timeout 600s;
        fastcgi_param SCRIPT_FILENAME {{root}}dev/$fastcgi_script_name;
        fastcgi_param APPLICATION_NAME {{app_name}};
        fastcgi_param PLATFORM dev;
        include fastcgi_params;
    }
}
