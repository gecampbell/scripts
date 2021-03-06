<?php
require 'vendor/autoload.php';

use OpenCloud\Rackspace;

define('MIN', 60);
define('HOUR', 60*MIN);
define('DAY', 24*HOUR);
define('WEEK', 7*DAY);
define('TTL', 12*HOUR);

// array maps old value of MX record to new value
$MXMAPPING = [
	'mx1.xlerb.com' => 'mx1.xlerb.email',
	'mx2.xlerb.com' => 'mx2.xlerb.email'
];

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('NOVA_API_KEY')
));

$service = $client->dnsService();
$domains = $service->domainList(true);
$changes = 0;
foreach($domains as $domain) {
    printf("%s (%s)\n", $domain->name(), $domain->emailAddress);
    // get all MX records
    $reclist = $domain->recordList(['type'=>'MX']);
    foreach($reclist as $record) {
    	$old = strtolower($record->data);
    	if (isset($MXMAPPING[$old])) {
    		$new = $MXMAPPING[$old];
    		printf("  - changing %s to %s\n",
    			$record->data, $new);
    		$record->data = $new;
    		$record->ttl = TTL;
    		$record->update();
    		++$changes;
    	}
    	else if ($record->ttl != TTL) {
    	    printf("  - setting TTL\n");
    	    $record->ttl = TTL;
    	    $record->update();
    	    ++$changes;
    	}
    }
}

printf("\n%s changed\n", $changes);
