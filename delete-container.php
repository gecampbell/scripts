<?php
require 'vendor/autoload.php';

use OpenCloud\Rackspace;
/**
 * set these for the values necessary
 */
define('REGION', '{REGION}');
define('CONTAINER', '{CONTAINER}');

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('OS_PASSWORD')
));

/**
 * Note - you may need to disable the CDN on a CDN-enabled container
 * before this will work.
 */

printf("Getting service [%s]...\n", REGION);
$service = $client->objectStoreService(null, REGION);

printf("Getting container [%s]...\n", CONTAINER);
$container = $service->getContainer(CONTAINER);

print "Deleting all objects...\n";
$container->deleteAllObjects();

print "Deleting the container...\n";
$container->delete();

print "Done\n";
