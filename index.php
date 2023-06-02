<?php
use Google\CloudFunctions\CloudEvent;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function(CloudEvent $cloudevent) {
    // Print the whole CloudEvent
    $stdout = fopen('php://stdout', 'wb');
    fwrite($stdout, $cloudevent);
};
