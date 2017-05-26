<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 18.05.17
 * Time: 19:23
 */

namespace Project\Cli;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use ObjectivePHP\Application\ApplicationInterface;
use ObjectivePHP\Cli\Action\AbstractCliAction;
use Pheanstalk\Pheanstalk;
use Project\Config\AnsibleApi;

/**
 * Class PlayBookWorker
 * @package Project\Listener
 */
class PlayBookWorker extends AbstractCliAction
{
    public function __construct()
    {
        $this->setCommand('ansible-worker');
        $this->setDescription('Send JSON String as a POST Request to AnsibleApi');
    }

    public function run(ApplicationInterface $app)
    {
        $ansible_api = $app->getConfig()->get(AnsibleApi::class);

        $pheanstalk = new Pheanstalk('127.0.0.1');

        while (true) {
            $job = $pheanstalk->watch('ansible-post')
                ->ignore('default')
                ->reserve();

            if ($job !== false) {
                try {
                    $client = new Client(
                        [
                            'base_uri' => $ansible_api["address"],
                            'headers' => ['Content-Type' => 'application/json']
                        ]
                    );

                    $response = $client->request('POST', '/post_data',
                        [
                            'json' => json_decode($job->getData())
                        ]
                    );

                    if ($response->getStatusCode() == 200) {
                        $pheanstalk->useTube('ansible-get')->put($response->getBody());
                        $pheanstalk->delete($job);
                    } else {
                        echo 'Request failed: HTTP status code: ' . $response->getStatusCode();
                    }
                } catch (RequestException $e) {
                    echo Psr7\str($e->getRequest());
                    if ($e->hasResponse()) {
                        echo Psr7\str($e->getResponse());
                    }
                }
            } else {
                sleep(3);
            }
        }
    }
}
