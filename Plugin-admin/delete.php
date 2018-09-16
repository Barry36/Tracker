<?php
require ('lib.php');
require ('query.php');
// This file is for DB delete triggers function

// Delete trigger
if (isset($_POST['deleteTriggerInfo'])) {
	$json = $_POST['deleteTriggerInfo'];
	$deleteTriggerInfo = (json_decode($json));
	deleteTrigger($deleteTriggerInfo);
}

// Delete condition
if (isset($_POST['currentConditionId'])) {
	$json = $_POST['currentConditionId'];
	$deleteConditionId = (json_decode($json));
	deleteCondition($deleteConditionId);
}
