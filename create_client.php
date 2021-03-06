<?php

require_once('include.php');
check_root();

echo "\nWARNING! No error checking, validation, or sanitation here! :)\n";

if(!is_test()){
	echo "\nEnter client full name: ";
	$client_full_name = trim(fgets(STDIN));
	//TODO validation of client_full_name

	echo "\nEnter client short name (only lowercase letters please): ";
	$client_short_name = trim(fgets(STDIN));
	
	if(strlen($client_short_name) < 1){
		echo 'Would be dangerous to continue without a client name!';
		exit;
	}

	echo "\nWhat domain do you want to run this site on?: ";
	$domain = trim(fgets(STDIN));

	//TODO validation of client_short_name

	echo "\nDo you want to create a CiviCRM database? (y/n) ";
	$input = trim(fgets(STDIN));
	if(strtolower($input) == 'y'){
		$databases[]='civicrm';
	}

	echo "\nDo you want to create a Drupal database? (y/n) ";
	$input = trim(fgets(STDIN));
	if(strtolower($input) == 'y'){
		$databases[]='drupal';
	}
} else {
	$client_full_name='Example';
	$client_short_name='ex';
	$domain='www.example.com';
	$databases[]='civicrm';
	$databases[]='drupal';	
}

$client_full_name_escaped = mysql_escape_string($client_full_name);


echo "\nAre you sure that you want to create a ".implode(' and ', $databases)." database for {$client_full_name} using the short name '{$client_short_name}'? (y/n) ";

$input = trim(fgets(STDIN));
if(strtolower($input) != 'y'){
	echo "\nOK - see ya!\n\n";
	exit;
}

ch_query("INSERT INTO client (name,full_name) VALUES ('{$client_short_name}', '{$client_full_name_escaped}')");
$client_id=mysql_insert_id();

foreach($databases as $database) {
	$query=ch_query("SELECT id, name FROM `database` WHERE `name` = '{$database}'",'force');
	$database=mysql_fetch_object($query);
	ch_query("INSERT INTO client_database (client_id,database_id) VALUES ({$client_id},".$database->id.")");
	ch_query("CREATE DATABASE {$client_short_name}_{$database->name}");
}

ch_query("GRANT ALL PRIVILEGES ON `{$client_short_name}\_%` .  * TO '{$client_short_name}'@'localhost' IDENTIFIED BY '{$client_short_name}'");

echo "\n* Created databases!\n";

//create standard vhost config file
$vhost_file=str_replace(array('xxDIRxx','xxDOMAINxx'),array($client_short_name, $domain), CIVIHOSTING_VHOST_TEMPLATE);

echo "\n* Wrote new configuration file!\n";

//make temporary file to get it out of php and still enable testing
$vhost_tempfilename="/tmp/civihosting.vhost.{$client_short_name}";
file_put_contents($vhost_tempfilename, $vhost_file);
ch_exec("mv {$vhost_tempfilename} /etc/apache2/sites-available/{$client_short_name}");

//create apache directory for vhost
$webroot="/var/www/$client_short_name";
$temp_webroot="/temp/$client_short_name";
echo"
You should PROBABLY ONE LINE AT A TIME! do the following...

sudo mkdir $temp_webroot
sudo cd $temp_webroot
sudo drush dl drupal
sudo mv /temp/$client_short_name/drupal* $webroot
sudo chown -R www-data:www-data {$webroot}
sudo cp -p {$webroot}/sites/default/default.settings.php {$webroot}/sites/default/settings.php
sudo a2ensite {$client_short_name}
sudo apache2ctl graceful

";
echo "\n* Sorted! ... I think! :p\n";


?>