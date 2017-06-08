# AnsibleWeb

The purpose of this open-source project is to have a frontend application which allows
us to send ansible playbooks in JSON format to [AnsibleApi](http://https://github.com/skrijeljhasib/AnsibleApi).
At the moment you can install a virtual server with packages and other services in openstack cloud.

## Getting started

AnsibleWeb has been tested with php7.0, apache2, mysql 5.7. It may work with alternative versions but is not currently documented.

### Prerequisites

Look at: https://github.com/skrijeljhasib/AnsibleApi

```
sudo apt-get update
sudo apt-get upgrade
sudo apt-get install apache2
sudo apt-get install mysql-server
sudo apt-get install php7.0 php7.0-mysql php7.0-xml
sudo apt-get install libapache2-mod-php
sudo apt-get install beanstalkd
sudo apt-get install supervisor
sudo a2enmod rewrite
```

### Installation

Download the project and place it in your Apache2 DocumentRoot folder.

Look for composer: https://getcomposer.org/

Run composer inside the AnsibleWeb-x.y.z folder:
```
sudo --user=www-data composer install
```

### Configuration

* Set Apache DocumentRoot example: /var/www/html/AnsibleWeb-x.y.z/public
* Set Apache Rewrite rule
* Create a database and grant user access to the database
* Copy app/config/doctrine.php to app/config/local/doctrine.local.php and add your values
* AnsibleWeb folder execute this:
```
vendor/bin/doctrine orm:schema-tool:create
```
* Copy app/config/ansible.php to app/config/local/ansible.local.php and add your values


#### Default Packages

```
INSERT INTO packages(name) VALUES
  ("munin-node"),
  ("postfix"),
  ("ghostscript"),
  ("gocr"),
  ("ocrad"),
  ("mailutils"),
  ("ntp"),
  ("cifs-utils"),
  ("pdftk"),
  ("libwww-perl"),
  ("libcache-cache-perl"),
  ("a2ps"),
  ("ccrypt"),
  ("poppler-utils"),
  ("zbar-tools"),
  ("imagemagick"),
  ("nfs-common"),
  ("beanstalkd"),
  ("binutils"),
  ("dirmngr"),
  ("fonts-lato"),
  ("freeipmi-common"),
  ("freeipmi-tools"),
  ("gnupg-agent"),
  ("gnupg2"),
  ("ldap-utils"),
  ("mutt"),
  ("zip"),
  ("unzip");
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
directory=/var/www/html/AnsibleWeb-x.y.z/
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
directory=/var/www/html/AnsibleWeb-x.y.z/
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
directory=/var/www/html/AnsibleWeb-x.y.z/
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/socket-worker-stderr.log
stdout_logfile=/var/log/supervisor/socket-worker-stdout.log
```

Info:
* You must probably change the **directory** attribute 

Run:
```
sudo beanstalkd restart
sudo service supervisor start
sudo supervisorctl reread
sudo supervisorctl update
sudo service supervisor restart
```

## Optional 
### OVH DNS
Look at: https://github.com/gheesh/ansible-ovh-dns

## Finally

* You must at first reload the machine list by clicking on the refresh button in the **List machine** page. After this you can create machines in the **Create your machine** page.

## Contributors

* Skrijelj Hasib

## License
The GNU General Public License v3.0 - GPL-3.0
