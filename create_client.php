<?php

require_once('connect_to_database.php');

echo "\nWARNING! No error checking, validation, or sanitation here! :)\n";

echo "\nEnter client full name: ";
$client_full_name = trim(fgets(STDIN));
$client_full_name_escaped = mysql_escape_string($client_full_name);
//TODO validation of client_full_name

echo "\nEnter client short name (only lowercase letters please): ";
$client_short_name = trim(fgets(STDIN));
$client_short_name_escaped = mysql_escape_string($client_short_name);

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

echo "\nAre you sure that you want to create a ".implode(' and ', $databases)." database for {$client_full_name} using the short name '{$client_short_name}'? (y/n) ";

$input = trim(fgets(STDIN));
if(strtolower($input) != 'y'){
	echo "\nOK - see ya!\n";
	exit;
}

mysql_query("INSERT INTO client (name,full_name) VALUES ('{$client_short_name_escaped}', '{$client_full_name_escaped}')\n");
$client_id=mysql_insert_id();

foreach($databases as $database) {
	$database=mysql_fetch_object(mysql_query("SELECT id, name FROM `database` WHERE `name` = '{$database}'"));
	mysql_query("INSERT INTO client_database (client_id,database_id) VALUES ({$client_id},".$database->id.")\n");
	mysql_query("CREATE DATABASE {$client_short_name}_{$database->name}\n");
}

mysql_query("GRANT ALL PRIVILEGES ON `[{$client_short_name}]\_%` .  * TO '[{$client_short_name}]'@'localhost' IDENTIFIED BY '[{$client_short_name}]'");

echo "\nSorted! ... I think! :p\n";

?>