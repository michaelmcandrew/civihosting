<?php
mysql_connect('localhost', 'root', 'root');
mysql_select_db('civihosting');

function ch_query($query){
	if(TEST != 1){
		return mysql_query($query);
	} else {
		echo "\n$query\n";
	}
}

function ch_exec($command){
	if(TEST != 1){
		return exec($command);
	} else {
		echo "\n$command\n";
	}
}

function check_root(){
	if($_ENV['USER'] !='root'){
		echo "\nYou are not root - byeeeee!\n\n";
		exit;
	}
}

?>