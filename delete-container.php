<?php
require 'vendor/autoload.php';

use OpenCloud\Rackspace;
/**
 * set these for the values necessary
 */
define('REGION', 'DFW');
define('CONTAINER', '<yourname>');

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('OS_PASSWORD')
));

/**
 * Note - you may need to disable the CDN on a CDN-enabled container
 * before this will work.
 */

print "Getting service...\n";
$service = $client->objectStoreService(null, REGION);

print "Getting container...\n";
$container = $service->getContainer(CONTAINER);

print "Deleting all objects...\n";
$container->deleteAllObjects();

print "Deleting the container...\n";
$container->delete();

print "Done\n";
