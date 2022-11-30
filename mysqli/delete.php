<?php

	include('includes/actions.php');
	global $sadb;
	
	$sadb->delete('Users','id=12'); // Table name, WHERE conditions
	$res = $sadb->getResult();
	
	echo "<pre>";
	print_r($res);
	echo "</pre>";
	
?>
