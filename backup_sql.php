<?php
// connect to database

if(!in_array($argv[1], array('daily','weekly','monthly'))){
	echo "Oops, you didn't enter a valid command line variable (daily, weekly, monthly)\n";
	exit;
}

$time_period=$argv[1];

require_once('connect_to_database.php');
$databases=mysql_query("SELECT c.name as client, d.name as `database`, concat(c.name, '_', d.name) as name FROM `client_database` AS cd
JOIN `client` AS c ON cd.client_id=c.id
JOIN `database` AS d ON cd.database_id=d.id
ORDER BY 1 ASC");
while($database=mysql_fetch_object($databases)){
	$filename="{$database->client}.".date('Ymd-Hi').".{$database->database}.sql";
	$path="/backup/clients/{$database->client}/sql/{$time_period}";
	if(!file_exists($path)){
		echo "Creating directory $path\n";
		mkdir($path, 0777, 1);
	}
	echo "Dumping database {$database->name}\n";
	exec("mysqldump {$database->name} > {$path}/{$filename}");
	echo "Zipping {$filename}\n";
	exec("gzip {$path}/{$filename}");
	
};


/**twice daily backup

1) get the list of databases to backup

2) back them up



**/






?>