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

define('CIVIHOSTING_VHOST_TEMPLATE', '<VirtualHost *:80>
	
        ServerName xxDOMAINxx
        # ServerAlias [alias] # if necessary
	ServerAdmin webmaster@thirdsectordesign.org

	DocumentRoot /var/www/xxDIRxx
	<Directory />
		Options FollowSymLinks
		AllowOverride All
	</Directory>
	<Directory /var/www/xxDIRxx/>
		Options Indexes FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>

	ScriptAlias /cgi-bin/ /usr/lib/cgi-bin/
	<Directory "/usr/lib/cgi-bin">
		AllowOverride None
		Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
		Order allow,deny
		Allow from all
	</Directory>

	ErrorLog /var/log/apache2/error.log

	# Possible values include: debug, info, notice, warn, error, crit,
	# alert, emerg.
	LogLevel warn

	CustomLog /var/log/apache2/access.log combined

    Alias /doc/ "/usr/share/doc/"
    <Directory "/usr/share/doc/">
        Options Indexes MultiViews FollowSymLinks
        AllowOverride None
        Order deny,allow
        Deny from all
        Allow from 127.0.0.0/255.0.0.0 ::1/128
    </Directory>

</VirtualHost>
');

?>