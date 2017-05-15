<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 11.05.17
 * Time: 17:09
 */

namespace Project\Service;

use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
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
     * @param $app_get ParameterContainerInterface
     */
    public function load($machine_access,$app_get)
    {
        $this->playbook = new PlayBook();

        $this->playbook->setName('Install and Configure MySQL');
        $this->playbook->setHosts('{{ lookup(\'file\', \'/tmp/'.$app_get->get('tmp_file').'\') }}');
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
     * @param $app_get ParameterContainerInterface
     * @return string
     */
    public function mongodb($app_get)
    {

        $apt = new Apt();
        $apt->setState(Apt::PRESENT);
        $apt->setAName('mongodb');

        $mongodb = new MongoDBUser();
        $mongodb->setUName($app_get->get('mongodb_new_user'));
        $mongodb->setPassword($app_get->get('mongodb_new_user_password'));
        $mongodb->setDatabase($app_get->get('mongodb_database'));
        $mongodb->setState('present');

        $this->playbook->setTask($apt->toArray());
        $this->playbook->setTask($mongodb->toArray());

        $playbook_json = $this->playbook->toJSON();

        return $playbook_json;
    }
}