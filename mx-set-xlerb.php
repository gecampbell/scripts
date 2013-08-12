<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

// This script generates the /etc/mail/local-host-names file for all
// domains that match the specified MX_HOST. I've found that maintaining
// that file manually is a big pain, so I made this to automatically
// generate it for my mail server.

require_once "php-opencloud.php";

define('AUTHURL', RACKSPACE_US);
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

$DOMAINLIST = array(
	'dontuseemail.com',
	'garnerroad.com',
	'techbreakfast.net',
	'techbreakfast.org'
);
$RECLIST = array(
	array(10 => 'mx1.xlerb.com.'),
	array(20 => 'mx2.xlerb.com.')
);

// uncomment for debug output
//setDebug(TRUE);

// establish our credentials
$cloud = new \OpenCloud\Rackspace(AUTHURL,
	array( 'username' => USERNAME,
		   'apiKey' => APIKEY ));

$dns = $cloud->DNS();

// list all domains
$dlist = $dns->DomainList();
while($domain = $dlist->Next()) {
	if (in_array($domain->Name(), $DOMAINLIST)) {
		printf("Deleting old MX records...");
		$reclist = $domain->RecordList(array('type'=>'MX'));
		while($r = $reclist->Next()) {
			$r->Delete();
			print(".");
		}
		print("done\n");
		printf("Updating %s...\n", $domain->Name());
		$rec = $domain->Record();
		$rec->type = 'MX';
		$rec->ttl = 3600;
		$rec->name = $domain->Name();
		foreach($RECLIST as $item) {
			foreach($item as $priority => $location) {
				printf("  %d %s...", $priority, $location);
				$rec->priority = $priority;
				$rec->data = $location;
				$rec->Create();
				printf("done\n");
			}
		}
	}
}
