<?php
	
	include('includes/actions.php');
	global $sadb;
	
	if(isset($_POST["import"]) && ""!=$_POST["import"]) 						// form was submitted
	{
		$table_name = "list";
		$file_name = $_FILES['file_source']['tmp_name'];						// imported file
		$columns = array("Date", "SKU Number", "product", "price");		// array of columns
  #public function csv_imports($file_name, $table_name, $file_heading, $columns=array() ){
		$result = $sadb->csv_imports($file_name, $table_name, false, $columns);	
		// importing file into database - file name, table name, array of columns, file has heading
		
		if($result){
			echo "Data imported successfully";
		}
	}

?>

<!DOCTYPE HTML>
<html>
<head>
	<title>CSV import</title>
</head>

<body>

	<h2>CSV import</h2>
	
	<form method="post" action="" enctype="multipart/form-data">
		
		<fieldset>
			<label>Import CSV File</label>
			<input type="file" name="file_source" id="file_source" class="edt" value="">
		</fieldset>	
		
		<fieldset>
			<label>&nbsp;</label>
			<input type="Submit" name="import" value="Import" onclick=" var s = document.getElementById('file_source'); if(null != s && '' == s.value) {alert('Define file name'); s.focus(); return false;}" />
		</fieldset>
		
	</form>

</body>
</html>
