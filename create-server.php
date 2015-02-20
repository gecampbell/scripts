<?php
require 'vendor/autoload.php';

use OpenCloud\Rackspace;

date_default_timezone_set('UTC');

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('OS_PASSWORD')
));
$client->authenticate();
$compute = $client->computeService(NULL, '{REGION}');
$server = $computeService->server();
$server->create(array(
        'name' => '{SERVERNAME}',
        'imageId' => '{IMAGE_ID}',
        'flavorId' => '{FLAVOR_ID}'
    ))
