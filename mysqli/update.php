<?php

	include('includes/actions.php');
	include('includes/dom.inc.php');
	include('includes/template.inc.php');
	include('includes/dbh.inc.php');
	global $sadb;
	$form = new Dom();
	$inputs = array( "User_Name" => "text", "Password_hash" => "password", "email" => "text");
############################################################
#$this->form_new_list($array($tag = $type)) returns $this->form = $form;
# Data flag           $this->post_data_inputs = $insert;
# Error flag array    $this->input_flag = $data_Err;
#############################################################
$form->form_new_list($inputs);
echo $form->form;
print_r($form->post_data_inputs);
echo $form->input_flag;
#  public function updates($table,$where,$params=array()){
	$status = ($form->input_flag === 'TRUE' ?	
	$sadb->updates('Users','id="9"',$form->post_data_inputs) // Table name, column names and values, WHERE conditions
	:
	'someting went wrog ');
	echo $status;
	$res = $sadb->getResult();
	
	echo "<pre>";
	print_r($res);
	echo "</pre>";
	
?>
