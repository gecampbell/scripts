<?php
require 'vendor/autoload.php';

// dumps zone files for all domains
use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => getenv('OS_USERNAME'),
    'apiKey'   => getenv('OS_PASSWORD')
));

$service = $client->dnsService();
$domains = $service->domainList(true);
foreach($domains as $domain) {
    $outfile = sprintf("%s.zone", $domain->name());
    printf("Exporting %s to %s...", $domain->name(), $outfile);
    $asyncResponse = $domain->export();
    $body = $asyncResponse->waitFor('COMPLETED');
    $fp = fopen($outfile, 'w');
    fputs($fp, $body['contents']);
    fclose($fp);
    printf("\n");
}
