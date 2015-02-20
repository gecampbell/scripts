<?php
require 'vendor/autoload.php';

// set this to the domain(s) that hold your primary MX server
$MXHOSTS = ['mx1.xlerb.com','mx1.xlerb.email'];

use OpenCloud\Rackspace;

date_default_timezone_set('UTC');

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('OS_PASSWORD')
));
$client->authenticate();
$compute = $client->computeService(NULL, 'DFW');
$server = $computeService->server();
$server->create(array(
        'name' => 'NewServer',
        'imageId' => '',
        'flavorId' => ''
    ))
