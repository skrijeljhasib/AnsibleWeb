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
sudo vi /etc/supervisor/conf.d/queue.conf
```
Add:
```
[program:ansible-queue]
command=php vendor/bin/op ansible-worker
directory=/var/www/html/AnsibleWeb/
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/ansible-worker-stderr.log
stdout_logfile=/var/log/supervisor/ansible-worker-stdout.log
```
Open:
```
sudo vi /etc/supervisor/conf.d/update-hosts-table.conf
```
Add:
```
[program:get-hosts-ansible]
command=php vendor/bin/op get-hosts-worker
directory=/var/www/html/AnsibleWeb/
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/get-hosts-worker-stderr.log
stdout_logfile=/var/log/supervisor/get-hosts-worker-stdout.log
```
Open:
```
sudo vi /etc/supervisor/conf.d/websocket.conf
```
Add:
```
[program:websocket]
command=php vendor/bin/op websocket-worker
directory=/var/www/html/AnsibleWeb/
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/websocket-stderr.log
stdout_logfile=/var/log/supervisor/websocket-stdout.log

```
Run:
```
sudo supervisorctl reread
sudo supervisorctl update
sudo service supervisor restart
```

## Contributors

* Skrijelj Hasib

## License
The GNU General Public License v3.0 - GPL-3.0
