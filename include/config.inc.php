<?php
	# configuration for database
	$_config['database']['hostname'] = "localhost";
	$_config['database']['username'] = "admin";
	$_config['database']['password'] = "admin123";
	$_config['database']['database'] = "infoact_consultas";
	
	# connect the database server
	$link = new mysqldb();
	$link->connect($_config['database']);
	// $link->selectdb($_config['database']['database']);
	// $link->query("SET NAMES 'utf8'");
	
	@session_start();
?>
