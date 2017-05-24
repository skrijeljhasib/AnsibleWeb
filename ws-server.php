<?php
use Hoa\Event\Bucket;
use Pheanstalk\Pheanstalk;

require __DIR__ . '/vendor/autoload.php';

$pheanstalk = new Pheanstalk('127.0.0.1');

$websocket = new Hoa\Websocket\Server(
    new Hoa\Socket\Server('ws://0.0.0.0:9000')
);

$websocket->on('open', function () {
    echo 'new connection', "\n";

    return;
});

$websocket->on('message', function (Bucket $bucket) use ($pheanstalk) {

    $job = $pheanstalk->watch('ansible-get')
        ->ignore('default')
        ->reserve();

    $bucket->getSource()->send($job->getData());

    $pheanstalk->delete($job);

    return;
});

$websocket->on('close', function () {
    echo 'connection closed', "\n";

    return;
});

$websocket->run();