<?php
require 'vendor/autoload.php';

use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('NOVA_API_KEY')
));

date_default_timezone_set('UTC');
printf("# local-host-names.php\n");
printf("# %s\n", date(DateTime::ISO8601));

$service = $client->dnsService();
$domains = $service->domainList(true);
foreach($domains as $domain) {
    if (isMX($domain))
        printf("%s\n", $domain->name());
}

function isMX($domain) {
    #print "--".$domain->name()."\n";
    $recs = $domain->recordList(['type'=>'MX']);
    foreach($recs as $rec) {
        #print $rec->data."\n";
        if (strtolower($rec->data) == 'mx1.xlerb.com')
            return true;
    }
    return false;
}
