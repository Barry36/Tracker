<?php 

// Preview Your Template Here

$DB = mysqli_connect('localhost','root','wjq123','beanstalk_moodle');

if (isset($_POST['templateName'])) {
	$templateName = $_POST['templateName'];
}else{
	echo "No Content";
}

$sql = "
			UPDATE mdl_bar_preview_tmp
			SET Bar_name = '$templateName'
			WHERE ID = 1;
		";
		
if ($DB->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }

