```
server {
  listen 80;
  server_name bto;
  root /home/user/www/bto;

  index index.php;
  
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

  location / {
    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass unix:/var/run/php5-fpm.sock;
            fastcgi_index index.php;
            include fastcgi_params;
    }
  }
}

```