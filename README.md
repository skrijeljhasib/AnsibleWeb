# AnsibleWeb


## Getting started


### Prerequisites

```
sudo apt-get install apache2
sudo apt-get install mysql-server
sudo apt-get install libapache2-mod-php
sudo apt-get install php-mysql
sudo apt-get install beanstalkd
sudo apt-get install supervisor
sudo a2enmod rewrite
```

### Installation

Download the project and place it in your Apache2 DocumentRoot folder.

Run composer inside the AnsibleWeb Folder:
```
composer install
```

### Apache Configuration file example :

```
DocumentRoot /var/www/html/ansibleweb/public/index.php
```

### Ansible and Database Configuration

* Copy app/config/ansible.php to app/config/local/ansible.local.php and add your values
* Copy app/config/doctrine.php to app/config/local/doctrine.local.php and add your values
* AnsibleWeb folder execute this:
```
vendor/bin/doctrine orm:schema-tool:create
```


#### SQL

```

```


### Supervisor
Open:
```
sudo vi /etc/supervisor/conf.d/get-worker.conf
```
Add:
```
[program:get-worker]
command=php vendor/bin/op get-worker
directory=/var/www/html/AnsibleWeb/
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/get-worker-stderr.log
stdout_logfile=/var/log/supervisor/get-worker-stdout.log
```
Open:
```
sudo vi /etc/supervisor/conf.d/post-worker.conf
```
Add:
```
[program:post-worker]
command=php vendor/bin/op post-worker
directory=/var/www/html/AnsibleWeb/
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/post-worker-stderr.log
stdout_logfile=/var/log/supervisor/post-worker-stdout.log
```
Open:
```
sudo vi /etc/supervisor/conf.d/socket-worker.conf
```
Add:
```
[program:socket-worker]
command=php vendor/bin/op socket-worker
directory=/var/www/html/AnsibleWeb/
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/socket-worker-stderr.log
stdout_logfile=/var/log/supervisor/socket-worker-stdout.log

```
Run:
```
sudo beanstalkd restart
sudo service supervisor start
sudo supervisorctl reread
sudo supervisorctl update
sudo service supervisor restart
```

## Contributors

* Skrijelj Hasib

## License
The GNU General Public License v3.0 - GPL-3.0
