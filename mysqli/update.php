<?php

	include('includes/actions.php');
	global $sadb;
	
#  public function updates($table,$where,$params=array()){
	$sadb->updates('Users','id="9" AND User_name="Name 5"',array('User_Name'=>"Name 4",'email'=>"name4@email.com")); // Table name, column names and values, WHERE conditions
	$res = $sadb->getResult();
	
	echo "<pre>";
	print_r($res);
	echo "</pre>";
	
?>
