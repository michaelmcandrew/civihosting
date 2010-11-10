<?php
// connect to database
require_once('include.php');
check_root();

if(!in_array($argv[1], array('daily','weekly','monthly'))){
	echo "Oops, you didn't enter a valid command line variable (daily, weekly, monthly)\n";
	exit;
}

$time_period=$argv[1];

$databases=mysql_query("SELECT c.name as client, d.name as `database`, concat(c.name, '_', d.name) as name FROM `client_database` AS cd
JOIN `client` AS c ON cd.client_id=c.id
JOIN `database` AS d ON cd.database_id=d.id
ORDER BY 1 ASC");
while($database=mysql_fetch_object($databases)){
	$filename="{$database->client}.".date('Ymd-Hi').".{$database->database}.sql";
	$path="/backup/clients/{$database->client}/sql/{$time_period}";
	if(!file_exists($path)){
		echo "Creating directory $path\n";
		mkdir($path, 0755, 1);
	}
	echo "Dumping database {$database->name}\n";
	ch_exec("mysqldump {$database->name} > {$path}/{$filename}");
	echo "Zipping {$filename}\n";
	ch_exec("gzip {$path}/{$filename}");
	
};
?>