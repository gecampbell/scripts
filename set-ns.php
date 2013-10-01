<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

/*
 * points the nameservers to ns1.glenc.co and ns2.glenc.co
 */

require_once "php-opencloud.php";

define('AUTHURL', RACKSPACE_US);
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

define('NEW_TTL', 60*60*24); // one day
define('NEW_DOMAIN', 'xlerb.com');

$DOMAINS = array(
	'20yearsincode.com',
	'broadpool.com',
	'broadpool.info',
	'broadpool.io',
	'broadpool.net',
	'broadpool.org',
	'campbell.io',
	'campbells.net',
	'contaxg.com',
	'dailyfunnies.org',
	'dontuseemail.com',
	'doodlebug.me',
	'elamcampbell.com',
	'garnerroad.com',
	'gec.pw',
	'glen-campbell.com',
	'glenc.co',
	'glenc.info',
	'glenc.io',
	'glenc.us',
	'glencampbell.co',
	'gophercloud.com',
	'gophercloud.io',
	'ickl.me',
	'internet-status.info',
	'isfriendfeeddeadyet.com',
	'k6gec.com',
	'k6gec.net',
	'listserv.co',
	'mhumc.com',
	'mobilephoto.co',
	'mrscampbell.info',
	'mypencil.net',
	'mypencil.org',
	'ongarnerroad.com',
	'orts.pw',
	'php-opencloud.com',
	'pictr.io',
	'raxdrg.com',
	'siteframe.org',
	'squirl.io',
	'suburbanredneck.net',
	'suburbanredneck.org',
	'techbreakfast.net',
	'techbreakfast.org',
	/*'tedcampbell.com',*/
	'today-now.com',
	'xlerb.co',
	'xlerb.com',
	'xlerb.info'
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
	printf("%s...", $domain->Name());
	if (in_array($domain->Name(), $DOMAINS)) {
		// get all the NS records and delete them
		$reclist = $domain->RecordList(array('type'=>'NS'));
		while($rec = $reclist->Next()) {
			if ($rec->type == 'NS') {
				$parts = explode('.', $rec->data, 2);
				if ($parts[1] != NEW_DOMAIN) {
					$rec->data = $parts[0] . '.' . NEW_DOMAIN;
					$rec->ttl = NEW_TTL;
					$rec->update();
					print('+');
				}
				else
					print('-');
			}
			else
				print('.');
		}
	}
	else
		print('no');

	// done
	print("\n");
}
