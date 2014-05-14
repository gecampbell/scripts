<?php
require 'vendor/autoload.php';

use OpenCloud\Rackspace;

$WPDOMAINS = [
    'glen-campbell.com',
    'glenc.co',
    'glenc.io',
    'glencampbell.co',
    'k6gec.net',
    'k6gec.com'
];

define('TARGET_A',      '23.253.121.84');
define('TARGET_AAAA',   '2001:4800:7901:0:a325:deec:0:1');
define('TARGET_CNAME',  'dfw.xlerb.com');

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('NOVA_API_KEY')
));

$service = $client->dnsService();
$domains = $service->domainList(true);
foreach($domains as $domain) {
    if (in_array($domain->name(), $WPDOMAINS)) {
        printf("%s\n", $domain->name());
        foreach(['A','AAAA','CNAME'] as $type) {
            $recs = $domain->recordList(['type'=>$type]);
            foreach($recs as $record) {
                fixRecord($record);
            }
        }
    }
}

function fixRecord($record) {
    switch($record->type) {
    case 'A':
        $record->data = TARGET_A;
        break;
    case 'AAAA':
        $record->data = TARGET_AAAA;
        break;
    case 'CNAME':
        if (substr($record->name(),0,3) != 'www')
            return;
        $record->data = TARGET_CNAME;
        break;
    }
    $record->ttl = 3600;
    printf("  [%4s] %s %s (ttl: %d)\n",
        $record->type, $record->name(), $record->data, $record->ttl);
    $record->update();
}
