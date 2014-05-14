<?php
require 'vendor/autoload.php';

use OpenCloud\Rackspace;

$NEWEMAIL = 'support@xlerb.com';

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('NOVA_API_KEY')
));

$service = $client->dnsService();
$domains = $service->domainList(true);
foreach($domains as $domain) {
    printf("%s (%s)\n", $domain->name(), $domain->emailAddress);
    if ($domain->emailAddress != $NEWEMAIL) {
        $domain->emailAddress = $NEWEMAIL;
        $domain->update();
        printf("  - FIXED\n");
    }
}