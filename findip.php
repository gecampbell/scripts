<?php
require 'vendor/autoload.php';

// change this to find a specific IP
define('TARGET', '162.242.212.236');

use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('NOVA_API_KEY')
));

$service = $client->dnsService();
$domains = $service->domainList(true);
foreach($domains as $domain) {
    printf("%s\n", $domain->name());
    $rlist = $domain->recordList();
    foreach($rlist as $rec) {
    	if ($rec->data == TARGET)
    		printf(" >> %s %s %s\n", $rec->type, $rec->name(), $rec->data);
    }
}
