<?php

// Punch through the Pantheon edge cache. This is not required
// when building a full Drupal site, just so we don't worry about testing.
setcookie('NO_CACHE', '1');

print "Through the stunnel, hit the remote service\n";
print "\n port : " . PHP_CONSTANT;

$url = "https://site.com:443/v1/o/test";
$host = parse_url($url, PHP_URL_HOST);
$localhost = "127.0.0.1";
$resolve_host = array(sprintf("%s:%d:%s", $host, PHP_CONSTANT, $localhost));
print "\n resolve_host : " . implode($resolve_host);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RESOLVE, $resolve_host);
curl_setopt($ch, CURLOPT_PORT, PHP_CONSTANT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
$start = microtime(true);
$result = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);
print "<h2>Contacting Remote Service</h2>";
print "Queried remote service in " . (microtime(true) - $start) . " seconds";
print "<h2>Result</h2>";
print "<pre>";
