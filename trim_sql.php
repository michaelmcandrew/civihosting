<?php
require_once('include.php');
check_root();

$trim=array(
	'daily' => '-3 months',
	'weekly' => '-3 years',
	'monthly' => '-300 years'
	);

foreach($trim as $time_period => $limit){
	$delete_date=new DateTime($limit);
	$clients=ch_query("SELECT name FROM `client` ORDER BY 1 ASC", 'force');
	while($client=mysql_fetch_object($clients)){
		$path="/backup/clients/{$client->name}/sql/{$time_period}";
		// It might not always exist, for example if the monthly cron hasn't run yet.
		if(file_exists($path)){
			chdir($path);
			$dir_handle=opendir('.');
			while($file=readdir($dir_handle)){
				if ($file != "." && $file != "..") {
					//is this file older than the date at which we consider it too old to keep?
					$create_date=new DateTime('@'.filectime($file));
					if($create_date < $delete_date){
						ch_exec("rm $path/$file");
					}
				}
			}			
		}
	}
}
?>