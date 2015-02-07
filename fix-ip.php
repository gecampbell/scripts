<?php
require 'vendor/autoload.php';

// change this to find a specific IP
// the IPs on the left are replaced with the corresponding IP on the right
$TARGET = [
  '23.253.126.71' => '104.236.150.205',
  '2001:4800:7817:103:a325:deec:ff04:bfa4' => '2604:a880:1:20::45:c001'
];
$EXCLUDE = [ 'dfw1.xlerb.com' ];

use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('OS_PASSWORD')
));

$service = $client->dnsService();
$domains = $service->domainList(true);
foreach($domains as $domain) {
    printf("%s\n", $domain->name());
    $rlist = $domain->recordList();
    foreach($rlist as $rec) {
        if (isset($TARGET[$rec->data])) {
            printf(" >> %s %s %s...", $rec->type, $rec->name(), $rec->data);
            if (in_array($rec->name(), $EXCLUDE)) {
                printf("skipping\n");
            }
            else {
                $rec->data = $TARGET[$rec->data];
                $rec->update();
                printf("fixed\n");
            }
        }
    }
}
