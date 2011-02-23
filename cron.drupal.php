<?php
require_once('include.php');
$drupal_cron_clients=ch_query("SELECT client.name
FROM client_cron
JOIN client ON client.id = client_cron.client_id
JOIN cron ON cron.id = client_cron.cron_id
WHERE cron.name='drupal'", 'force');

while($client=mysql_fetch_object($drupal_cron_clients)){
	$exec[]="drush -r /var/www/{$client->name} cron";
}
ch_exec(implode(";\n", $exec));
?>
