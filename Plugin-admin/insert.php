<?php 
// This file is for saving trigger info and conditions to the DB
require ('lib.php');
require ('query.php');
$DB = mysqli_connect('localhost', 'root','wjq123','beanstalk_moodle');

// Insert default trigger info when creating new trigger
if (isset($_POST['isInsertNewTrigger'])) {
 	insertDefaultTrigger();
 } 


// Insert defualt condition when clicking add condition
if (isset($_POST['insertConditionTriggerId'])) {
	$json = $_POST['insertConditionTriggerId'];
	$insertConditionTriggerId = json_decode($json);
	insertDefaultCondition($insertConditionTriggerId);
	getLatestConditionId();
}
