# WT
Repository for university subject web technologies


```nginx
server {
    listen 80 default_server;
    listen [::]:80 default_server;

    root /home/mihail/WT;

    index index.php;

    server_name _;

    location / {
        # Перенаправление всех запросов на index.php
        rewrite ^ /index.php last;
    }

    location /admin {
        auth_basic "Administrator’s Area";
        auth_basic_user_file /etc/apache2/.htpasswd;
        rewrite ^ /index.php last;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }
    
    location ~ \.css$ {
  	add_header Content-Type text/css;
    }
    
    location ~ \.js$ {
    	types { }
    	default_type application/javascript;
    	add_header Content-Type application/javascript;
    }
}
```
