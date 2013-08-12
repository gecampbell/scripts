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

define('DNS_EMAIL_ADDRESS', 'dns@xlerb.com');

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
	$domain->emailAddress = DNS_EMAIL_ADDRESS;
	$domain->Update();
}
