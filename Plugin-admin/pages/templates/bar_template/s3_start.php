<?php

 	use Aws\S3\S3Client;
 	
 	require '../../../vendor/autoload.php';

 	$config = require('s3_config.php');


 	// S3
 	$s3 = S3Client::factory(array(
	'version' => 'latest',
  	'region'  => 'us-east-1',
  	'credentials' => array(
  	'key' => $config['s3']['key'],
  	'secret'  => $config['s3']['secret'],
  )
));

