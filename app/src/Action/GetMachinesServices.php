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
use stdClass;

class GetMachinesServices
{

    public function __invoke(Application $app)
    {
        try {
            $services_gateway = $app->getServicesFactory()->get('gateway.services');
            $services = $services_gateway->fetchByName($app->getRequest()->getParameters()->get('name'));
        } catch (Exception $e) {
            throw new Exception('Can not load services from DB');
        }

        if($services != null) {
            echo json_encode($services->toArray());
        } else {
            echo json_encode(new stdClass());
        }

    }

}
