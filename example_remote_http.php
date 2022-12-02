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

// Create a "connect_to" array that will tell libcurl how to send this request through our tunnel
$urlComponents = parse_url($url);
$host = $urlComponents['host'];

$ch = curl_init();

if (filter_var($host, FILTER_VALIDATE_IP)) {
  $newURL = sprintf("%s://%s:%s%s", $urlComponents['scheme'], 127.0.0.1, constant($soip_constant_name), $urlComponents['path']);
  if ($urlComponents['query'] ?? false) {
    $newURL .= '?' . $urlComponents['query']
  }

  print "\n soip_url : " . implode($newURL);
  curl_setopt($ch, CURLOPT_URL, $newURL );
  
  if(($urlComponents['user'] ?? false) && ($urlComponents['pass'] ?? false)) {
    curl_setopt($ch, CURLOPT_USERPWD, $urlComponents['user'] .':'. $urlComponents['pass']);
  }
} else {
  curl_setopt($ch, CURLOPT_URL, $url);
  $connect_to = array(sprintf("%s:443:127.0.0.1:%d", $host, constant($soip_constant_name)));
  print "\n connect_to : " . implode($connect_to);
  curl_setopt($ch, CURLOPT_CONNECT_TO, $connect_to);
}


curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
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
