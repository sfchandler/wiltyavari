<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$companyId = $_POST['companyId'];
$output_dir = 'img/'.$companyId.'/';
if(!empty($companyId)){
	if(isset($_FILES['file']))
	{
		if ($_FILES['file']['error'] > 0)
		{
		  echo 'Error: ' . $_FILES['file']['error'] . '<br>';
		}
		else
		{
		  if(!file_exists('img/'.$companyId)){
			mkdir('img/'.$companyId, 0777);
		  }
		  //$fileName = $_FILES['file']['name'];
		  $fileName = $companyId.'.'.pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		  $filePath = $output_dir. $fileName;
		  $update = updateCompanyLogo($mysqli,$filePath,$companyId);
		  if(($update == 'Added') || ($update == 'Updated')){	
			  if(move_uploaded_file($_FILES['file']['tmp_name'],$output_dir. $fileName)){
					echo getCompanyLogoById($mysqli,$companyId);
			  }else{
				  echo 'Error Uploading'.$_FILES['file']['error'];
			  }
		  }else{
			    echo 'Error Updating reference'.$update;
		  }
		}
	}
}else{
	echo 'Error Uploading';
}
?>