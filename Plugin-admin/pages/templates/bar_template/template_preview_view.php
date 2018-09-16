<?php

// Test purposes
$DB = mysqli_connect('localhost','root','wjq123','beanstalk_moodle');
require 'template_preview_reference_from_mdl.php';
require '../../../lib.php';

$sql = "
		SELECT Bar_name
		FROM mdl_bar_preview_tmp
		WHERE ID = 1
		";
if (!($result = $DB->query($sql))) {
	echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
}

while ($row = $result->fetch_assoc()){
    $Bar_name = $row['Bar_name'];
}    


// Get Bar Content From the trigger_bar table

$sql = "
		SELECT *
		FROM mdl_trigger_bar
		WHERE Bar_name = '$Bar_name'
		";

if (!($result = $DB->query($sql))) {
	echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
}

while ($value = $result->fetch_assoc()){
	$Bar_content = $value['Bar_content'];
    $Bar_title = $value['Bar_title'];
    $Bar_btnContent = $value['Bar_btn_content'];
    $Bar_mdlHeader = $value['Bar_mdl_header'];
    $Bar_mdlDesc = $value['Bar_mdl_description'];
    $Bar_promo_code = $value['Bar_promo_code'];
    $Bar_promolink = $value['promo_url'];
}

$userid = 'ExampleUserID';
// Replace Parameters
getCustomization($userid,$Bar_title,$Bar_btnContent,$Bar_mdlHeader,$Bar_mdlDesc,$Bar_promolink,$Bar_promo_code);    
echo "$Bar_content";
