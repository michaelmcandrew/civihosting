<?php
require_once('include.php');

$drupal_cron_clients=ch_query("SELECT client.name, client.base_url
FROM client_cron
JOIN client ON client.id = client_cron.client_id
JOIN cron ON cron.id = client_cron.cron_id
WHERE cron.name='civimail_send'");

while($client=mysql_fetch_object($drupal_cron_clients)){
	$exec[]="wget -O - -q -t 1 --post-file=/clients/{$client->name}/cronpostdata {$client->base_url}/sites/all/modules/civicrm/bin/civimail.cronjob.php";
}
exec(implode(";\n", $exec));
?>
