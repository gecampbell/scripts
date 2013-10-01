<?php
// (c)2012 Rackspace Hosting
// See COPYING for licensing information

/*
 * just lists all the domains
 */

require_once "php-opencloud.php";

define('AUTHURL', RACKSPACE_US);
define('USERNAME', $_ENV['OS_USERNAME']);
define('TENANT', $_ENV['OS_TENANT_NAME']);
define('APIKEY', $_ENV['NOVA_API_KEY']);

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
	printf("%s\n", $domain->Name());
}
