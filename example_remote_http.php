<?php

// Punch through the Pantheon edge cache. This is not required
// when building a full Drupal site, just so we don't worry about testing.
setcookie('NO_CACHE', '1');

// To test your Pantheon Enterprise Gateway, you will need two things:
// - the domain that corresponds to the IP address you provided or
//    the domain and query, if you'd like to test that
// - the service name you provided
// Replace https://remotesite.com/v1/o/test with your domain
// Replace YOUR_SERVICE_NAME with the service name you provided

// The $url should NOT contain a port number.
// The domain must match the domain on the external server SSL cert. 

// Example where the external service is at https://external-server.com
// and the service name is MY_SERVICE
// $url = "https://external-server.com/service?query=something&contentType=json'
// $soip_constant_name = "PANTHEON_SOIP_MY_SERVICE

// Example where the external service is at ldaps://ldap.external-server.com
// and the service name is MY_SERVICE
// $url = "ldaps://ldap.external-server.com/'
// $soip_constant_name = "PANTHEON_SOIP_MY_SERVICE

/////////////////
$url = "https://site.com/v1/o/test";
$soip_constant_name = "PANTHEON_SOIP_YOUR_SERVICE_NAME";
////////////////

print "Through the stunnel, hit the remote service\n";
print "\n port : " . constant($soip_constant_name);

// Create a "resolve_host" that will point to localhost and resolve externally
$host = parse_url($url, PHP_URL_HOST);
$localhost = "127.0.0.1";
$resolve_host = array(sprintf("%s:%d:%s", $host, constant($soip_constant_name), $localhost));
print "\n resolve_host : " . implode($resolve_host);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RESOLVE, $resolve_host);
curl_setopt($ch, CURLOPT_PORT, constant($soip_constant_name));
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);
$start = microtime(true);
$result = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);
print "<h2>Contacting Remote Service</h2>";
print "Queried remote service in " . (microtime(true) - $start) . " seconds";
if (isset($result)) {
  print "<h2>Result</h2>";
  print "<pre>";
  print htmlspecialchars($result);
  print "</pre>";
}
if (isset($error)) {
  print "<h2>Errors</h2>";
  print "<pre>";
  print htmlspecialchars($error);
  print "</pre>";
}
?>
