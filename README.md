操縱手冊：

1. 使用git clone 下載本系統
2. 搭配nginx server 設定如下

  ```
    server {
      listen 80;
      #your server name
      server_name buytogether;
      # 專案內index.php放置的位置
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
  
3. `sudo service nginx restart` 
4. buytogether->config->config.php and test.php，找`db`將資料庫設定完畢。
5. 在設定好的資料庫匯入test->db.sql
6. 要使用上傳相片功能請依照config.php->upload_* 相關設定建立資料夾(在linux環境請把資料夾權限設為777)
7. then it's work!! 