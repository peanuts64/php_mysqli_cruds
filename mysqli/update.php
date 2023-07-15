<?php
include('includes/actions.php');
include('includes/dom.inc.php');
include('includes/template.inc.php');
include('includes/dbh.inc.php');
global $sadb;
$form = new Dom();
$inputs = array( "User_Name" => "text",
	 "Password_hash" => "password",
	 "email" => "text");
$sadb->select('Users',
	 'User_Name,email',
	 NULL,
	 'id="' . (isset($_GET['id']) ? $_GET['id'] : 9) . '"', 'id DESC');
// Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
$res = $sadb->getResult();
$form->input_value_inject[0] =	$res[0];
echo "<pre>";
print_r($res);
echo "</pre>";
############################################################
#$this->form_new_list($array($tag = $type)) returns $this->form = $form;
# Data flag           $this->post_data_inputs = $insert;
# Error flag array    $this->input_flag = $data_Err;
#############################################################
$form->form_new_list($inputs);
echo $form->form;
$form->post_data_inputs['Password_hash'] = password_hash($form->post_data_inputs['Password_hash'], PASSWORD_DEFAULT);
$status = ($form->input_flag === 'TRUE' ?	
$sadb->updates('Users',
	'id="' . (isset($_GET['id']) ? $_GET['id'] : 9) . '"',
	$form->post_data_inputs) 
	// Table name, column names and values, WHERE conditions
	:
	'someting went wrog ');
#	echo $status;
$res = $sadb->getResult();
	
echo "<pre>";
print_r($res);
echo "</pre>";
	
?>
