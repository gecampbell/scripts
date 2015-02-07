<?php
require 'vendor/autoload.php';

use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('OS_PASSWORD')
));

$service = $client->dnsService();
$domains = $service->domainList(true);
$ndom = 0;
$nrec = 0;
foreach($domains as $domain) {
    ++$ndom;
    printf("%-5s (%s)\n", $domain->name(), $domain->emailAddress);
    $rlist = $domain->recordList();
    foreach($rlist as $rec) {
        ++$nrec;
        printf("  %4s %-24s %6d %s\n",
            $rec->type, $rec->name(), $rec->ttl, $rec->data);
    }
}

printf("\nDomains: %d\nRecords: %d\n", $ndom, $nrec);
