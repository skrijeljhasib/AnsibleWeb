<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 01.06.17
 * Time: 11:37
 */

namespace Project\Action;


use ObjectivePHP\Html\Exception;
use Project\Application;

class GetNewMachineData
{

    public function __invoke(Application $app)
    {
        try {
            $host_gateway = $app->getServicesFactory()->get('gateway.hosts');
            $host = $host_gateway->fetchByName($app->getRequest()->getParameters()->get('name'));
        } catch (Exception $e) {
            throw new Exception('Can not load host from DB');
        }

        echo json_encode($host->toArray());
    }

}