<?php

// Punch through the Pantheon edge cache. This is not required
// when building a full Drupal site, just so we don't worry about testing.
setcookie('NO_CACHE', '1');

print "Through the stunnel, hit the remote service";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "127.0.0.1");
curl_setopt($ch, CURLOPT_PORT, PANTHEON_SOIP_XXXXX_YOUR_SERVICE_NAME);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$start = microtime(true);
$result = curl_exec($ch);
curl_close($ch);
print "<h2>Contacting Remote Service</h2>";
print "Queried remote service in " . (microtime(true) - $start) . " seconds";
print "<h2>Result</h2>";
print "<pre>";
print htmlspecialchars($result);
print "</pre>";
?>