<?php
// connect to database

require_once('connect_to_database.php');

$clients=mysql_query("SELECT name FROM `client` ORDER BY 1 ASC");
while($client=mysql_fetch_object($clients)){
	$path="/var/www/{$client->name}/sites/default/files/";
	if(file_exists($path)){
		$date=date('Ymd-Hi');
		$backup_dir="/backup/clients/{$client->name}/files";
		if(!file_exists($backup_dir)){
			mkdir($backup_dir, 0755, 1);
		}
		exec("rsync -aP --link-dest={$backup_dir}/latest {$path} {$backup_dir}/$date\n");
		exec("rm -f {$backup_dir}/latest\n");
		exec("ln -s {$backup_dir}/$date {$backup_dir}/latest\n");
	}
};
?>