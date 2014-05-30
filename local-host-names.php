<?php
require 'vendor/autoload.php';

// set this to the domain(s) that hold your primary MX server
$MXHOSTS = ['mx1.xlerb.com','mx1.xlerb.email'];

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
	global $MXHOSTS;
    #print "--".$domain->name()."\n";
    $recs = $domain->recordList(['type'=>'MX']);
    foreach($recs as $rec) {
        #print $rec->data."\n";
        if (in_array(strtolower($rec->data), $MXHOSTS))
            return true;
    }
    return false;
}
