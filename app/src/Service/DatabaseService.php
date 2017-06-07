<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 11.05.17
 * Time: 17:09
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use Project\Application;
use Project\Entity\JSON\Apt;
use Project\Entity\JSON\MongoDBUser;
use Project\Entity\JSON\MySQLDB;
use Project\Entity\JSON\MySQLUser;
use Project\Entity\JSON\PlayBook;

class DatabaseService
{

    /**
     * @var PlayBook
     */
    private $playbook;

    /**
     * @param $machine_access array
     * @param $app Application
     */
    public function load($machine_access, $app)
    {
        $this->playbook = new PlayBook();

        $this->playbook->setHosts($app->getRequest()->getParameters()->get('ip'));
        $this->playbook->setConnection('ssh');
        $this->playbook->setRemoteUser($machine_access['remote_user']);
        $this->playbook->setBecome('true');
        $this->playbook->setBecomeMethod('sudo');
        $this->playbook->setBecomeUser('root');
        $this->playbook->setGatherFacts('false');
    }


    /**
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function mysql($app_get)
    {

        $this->playbook->setName('Install and Configure MySQL');

        $apt = new Apt();
        $apt->setState(Apt::PRESENT);
        $apt->setAName(Apt::MULTIPLE_ITEMS);
        $apt->setWithItems(['mysql-server','mysql-client','python-mysqldb']);

        $mysql_user_root = new MySQLUser();
        $mysql_user_root->setUName('root');
        $mysql_user_root->setPassword($app_get->get('mysql_root_password'));
        $mysql_user_root->setUpdatePassword('always');

        $mysql_db = new MySQLDB();
        $mysql_db->setDName($app_get->get('mysql_database'));
        $mysql_db->setState('present');
        $mysql_db->setLoginUser('root');
        $mysql_db->setLoginPassword($app_get->get('mysql_root_password'));

        $mysql_new_user = new MySQLUser();
        $mysql_new_user->setUName($app_get->get('mysql_new_user'));
        $mysql_new_user->setPassword($app_get->get('mysql_new_user_password'));
        $mysql_new_user->setPriv($app_get->get('mysql_database').'.*:ALL');
        $mysql_new_user->setState('present');
        $mysql_new_user->setLoginUser('root');
        $mysql_new_user->setLoginPassword($app_get->get('mysql_root_password'));

        $this->playbook->setTask($apt->toArray());
        $this->playbook->setTask($mysql_user_root->toArray());
        $this->playbook->setTask($mysql_db->toArray());
        $this->playbook->setTask($mysql_new_user->toArray());

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }

    /**
     * @return string
     */
    public function mongodb()
    {
        $this->playbook->setName('Install MongoDB');

        $apt = new Apt();
        $apt->setState(Apt::PRESENT);
        $apt->setAName(Apt::MULTIPLE_ITEMS);
        $apt->setWithItems(['mongodb','python-pymongo']);

        $this->playbook->setTask($apt->toArray());

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }
}
