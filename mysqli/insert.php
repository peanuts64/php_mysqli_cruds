<?php
	
	include('includes/actions.php');
	global $sadb;
	
#  public function insert($table,$params=array()) {
	$sadb->insert('Users',array('User_Name'=>'Name 5', 'Password_hash' => 'hash','email'=>'name5@email.com')); // Table name, column names and respective values
	$res = $sadb->getResult();
	
	echo "<pre>";
	print_r($res);
	echo "</pre>";
	
?>	
