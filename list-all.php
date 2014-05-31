<?php
require 'vendor/autoload.php';

use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('NOVA_API_KEY')
));

$service = $client->dnsService();
$domains = $service->domainList(true);
foreach($domains as $domain) {
    printf("%-5s (%s)\n", $domain->name(), $domain->emailAddress);
    $rlist = $domain->recordList();
    foreach($rlist as $rec) {
        printf("  %4s %-24s %6d %s\n",
            $rec->type, $rec->name(), $rec->ttl, $rec->data);
    }
}
