```
server {
  listen 80;
  server_name buytogether;
  root /home/user/www/buytogether/www;

  index index.html index.htm index.php;

    location ~ \.php {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
    
    location / {
        try_files $uri @php;
    }
    
    location @php {
        fastcgi_intercept_errors on;
        fastcgi_split_path_info ^(.+)(\?/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root/index.php;
        fastcgi_param GLEG_CONFIG work;
    }
}
```

```
location /pma/ {
        alias /usr/share/phpmyadmin/;
        index index.php;
        location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
          fastcgi_pass unix:/var/run/php5-fpm.sock;
          fastcgi_index index.php;
          include fastcgi_params;
        }
  }

```
