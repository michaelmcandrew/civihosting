<?php
require_once('include.php');
check_root();
 
$clients=ch_query("SELECT name FROM `client` ORDER BY 1 ASC",'force');
while($client=mysql_fetch_object($clients)){
	$path="/var/www/{$client->name}/sites/default/files/";
	if(file_exists($path)){
		$date=date('Ymd-Hi');
		$backup_dir="/backup/clients/{$client->name}/files";
		if(!file_exists($backup_dir)){
			ch_exec("mkdir -p $backup_dir");
			ch_exec("chmod 0755 $backup_dir");
			
		}
		ch_exec("rsync -aP --link-dest={$backup_dir}/latest {$path} {$backup_dir}/$date");
		ch_exec("rm -f {$backup_dir}/latest");
		ch_exec("ln -s {$backup_dir}/$date {$backup_dir}/latest");
	}
};
?>