<?php 
// This file contains AWS s3 uploading files API

require 's3_start.php';
use Aws\S3\Exception\S3Exception;

if ($_FILES["file"]["name"]!='') {


	// File Details
	$file = $_FILES['file'];
	// $promo_code = $_FILES['promo_code'];
	$file_name = $file['name'];
	$tmp_name = $file['tmp_name'];

	if (isset($_POST['promo_code'])) {
	 	$promo_code = $_POST['promo_code'];
	 }else{
	 	echo "Missing Promotion Code!";
	 }	

	$extension = explode('.', $file_name);
 	$extension = strtolower(end($extension));
 	$actual_name = "og:image-"."{$promo_code}.{$extension}";
 	// Temp Details
	$key = md5(uniqid());	
	$tmp_file_name = "{$key}.{$extension}";
	$tmp_file_path = "upload_test/{$tmp_file_name}";

	// Move the file
	if(!move_uploaded_file($tmp_name, $tmp_file_path)){
		die("There was an error when moving the uploaded file.");
	}else{
		move_uploaded_file($tmp_name, $tmp_file_path);
	}
	try{
		$s3->putObject([
			'Bucket' => $config['s3']['bucket'],
			'Key' => "images/nurseachieve/{$actual_name}",
			'Body' => fopen($tmp_file_path, 'rb'),
			'ACL' => 'public-read'
		]);
		echo "File Uploaded Successfully !";
		// Remove the file
		unlink($tmp_file_path);
	}catch(S3Exception $e){
		die("There was an error uploading the file.");
	}

}
?> 
