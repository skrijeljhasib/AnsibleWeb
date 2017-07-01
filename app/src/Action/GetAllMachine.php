<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 01.06.17
 * Time: 16:57
 */

namespace Project\Action;

use ObjectivePHP\Html\Exception;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Application\Action\AjaxAction;

class GetAllMachine extends AjaxAction
{

    public function run(ApplicationInterface $app)
    {
        try {
            $host_gateway = $app->getServicesFactory()->get('gateway.hosts');
            $dbhosts = $host_gateway->fetch();
        } catch (Exception $e) {
            throw new Exception('Can not load hosts from DB');
        }

        $hosts['data'] = [];

        foreach ($dbhosts as $host) {

            $host = $host->toArray();

            $btn = '';
            if ($host['status'] != 'ACTIVE') {
                $btn = 'disabled';
            }
            $action = '<form><input type="hidden" name="name" value="' . $host['name'] . '"><button type="button" ' . $btn . ' class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal"><i class="glyphicon glyphicon-trash" data-toggle="tooltip" title="Delete"></i></button>&nbsp<button type="button" ' . $btn . ' class="btn btn-primary" data-toggle="modal" data-target="#hostEditModal"><i class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Delete"></i></button></form>';

            $host['action'] = $action;

            $hosts['data'][] = $host;
        }
	
        return $hosts;
    }

}
