<?php
mysql_connect('localhost', 'root', 'root');
mysql_select_db('civihosting');

function ch_query($query){
	if(!TEST){
		return mysql_query($query);
	} else {
		echo "\n$query\n";
	}
}

function ch_exec($command){
	if(!TEST){
		return exec($command);
	} else {
		echo "\n$command\n";
	}
}

?>