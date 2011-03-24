<?php
require_once('include.php');
$drupal_cron_clients=ch_query("SELECT client.base_url
FROM client_cron
JOIN client ON client.id = client_cron.client_id
JOIN cron ON cron.id = client_cron.cron_id
WHERE cron.name='drupal'", 'force');

while($client=mysql_fetch_object($drupal_cron_clients)){
	$exec[]="wget -O - -q -t 1 {$client->base_url}/cron.php";	
}
ch_exec(implode(";\n", $exec));
?>
