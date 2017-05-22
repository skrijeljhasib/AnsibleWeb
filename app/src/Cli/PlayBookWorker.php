<?php
/**
 * Created by PhpStorm.
 * User: skrijeljhasib
 * Date: 18.05.17
 * Time: 19:23
 */

namespace Project\Cli;


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

        while (true)
        {
            $job = $pheanstalk->watch('ansible-json')
                ->ignore('default')
                ->reserve();

            $ch = curl_init($ansible_api["address"].'/post_data');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $job->getData());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($job->getData()))
            );

            $result = curl_exec($ch);

            if (curl_errno($ch))
            {
                echo 'Couldn\'t send request: ' . curl_error($ch) . '\n';
            }
            else
            {
                $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($resultStatus == 200)
                {
                    echo $result . '\n';
                    $pheanstalk->delete($job);
                }
                else
                {
                    echo 'Request failed: HTTP status code: ' . $resultStatus . '\n';
                }
            }

            curl_close($ch);
        }
    }
}