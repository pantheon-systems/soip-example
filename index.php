<?php

// Punch through the Pantheon edge cache. This is not required
// when building a full Drupal site, just so we don't worry about testing.
setcookie('NO_CACHE', '1');

$test_port = PANTHEON_SOIP_ICANHAZIP;
$test_https_port = PANTHEON_SOIP_ICANHAZIP_SSL;

print "This endpoint's actual ip, through icanhazip.com: ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "23.253.218.205");
curl_setopt($ch, CURLOPT_PORT, 80);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$start = microtime(true);
print curl_exec($ch);
curl_close($ch);
print "<br />... that took " . (microtime(true) - $start) . " seconds";
print "<br /><br />";

print "Through the stunnel, icanhazip.com service reports an ip: ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "127.0.0.1");
curl_setopt($ch, CURLOPT_PORT, $test_port);
// The hostname where the request should be sent.
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: example.com'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$start = microtime(true);
print curl_exec($ch);
curl_close($ch);
print "<br />... that took " . (microtime(true) - $start) . " seconds";
print "<br /><br />";

print "Through stunnel, except this time connecting to icanhazip.com on port 443: ";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://127.0.0.1");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_PORT, $test_https_port);
// The hostname where the request should be sent.
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: example.com'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$start = microtime(true);
print curl_exec($ch);
curl_close($ch);
print "<br />... that took " . (microtime(true) - $start) . " seconds";
