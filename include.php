<?php
mysql_connect('localhost', 'root', 'root');
mysql_select_db('civihosting');
array_walk($argv, 'lower');

function ch_query($query, $force=''){
	if(!is_test() || $force=='force'){
		return mysql_query($query);
	} else {
		echo "\n$query\n";
	}
}

function ch_exec($command){
	if(!is_test()){
		return exec($command);
	} else {
		echo "$command\n";
	}
}

function check_root(){
	if($_ENV['LOGNAME'] !='root'){
		echo "\nYou are not root - cannot continue.\n\n";
		exit;
	}
}

function lower(&$string){
   $string = strtolower($string);
}

function is_test(){
	global $argv;
	if(in_array('test',$argv)){
		return 1;
	}
}


?>