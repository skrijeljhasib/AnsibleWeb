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

            $btndel = '';
            $btnmod = '';
	    $btndbdel = '';
            if ($host['status'] != 'ACTIVE') {
                $btndel = 'disabled';
            }
	    if (($host['status'] != 'STATIC') && ($host['status'] != 'ACTIVE')) {
		$btnmod = 'disabled';
	    }
	    if ($host['status'] == 'DELETING') {
                $btndbdel = 'disabled';
            }
            if ($host['status'] == 'STATIC') {
                $modalEdit = '#hostEditStaticModal';
            } else {
		$modalEdit = '#hostEditModal';
	    }
            $action = '<form><input type="hidden" name="name" value="' . $host['name'] . '" /><input type="hidden" id="hostgroup" name="hostgroup" value="' . $host['hostgroup'] . '" /><input type="hidden" id="hostip" name="hostip" value="' . $host['ip'] . '" /><input type="hidden" id="hostlocation" name="hostlocation" value="' . $host['location'] . '" /><button type="button" ' . $btndel . ' class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal"><i class="glyphicon glyphicon-trash" data-toggle="tooltip" title="Delete"></i></button>&nbsp<button type="button" ' . $btnmod . ' class="btn btn-default" data-toggle="modal" data-target="' . $modalEdit . '"><i class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Edit"></i></button>&nbsp<button type="button" ' . $btndbdel . ' class="btn btn-default" data-toggle="modal" data-target="#confirmSoftDeleteModal"><i class="glyphicon glyphicon-trash" data-toggle="tooltip" title="Delete from DB"></i></button>&nbsp<button type="button" ' . $btndbdel . ' class="btn btn-default" data-toggle="modal" data-target="#deployAppModal"><i class="glyphicon glyphicon-expand" data-toggle="tooltip" title="Deploy Application"></i></button>
</form>';

            $host['action'] = $action;

            $hosts['data'][] = $host;
        }
	
        return $hosts;
    }

}
