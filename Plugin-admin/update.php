<?php
// This file is for updating trigger info and conditions to the DB

require ('lib.php');
require ('query.php');

// *********************************************************************************************************************************
// ***************                                       Update Trigger                                            ***************
// *********************************************************************************************************************************


// Save btn
if (isset($_POST['myTriggerInfo'])) {
	$json = $_POST['myTriggerInfo'];
	$updateTriggerInfo = json_decode($json);
	$triggerInfo = $updateTriggerInfo->triggerRecord;
	$arrayOfConditionInfo = $updateTriggerInfo->conditionRecord;

	if (isset($_POST['courseid'])) {
		$Trigger_id = $triggerInfo->triggerId;
		$courseid = $_POST['courseid'];
		updateCourseIDExistedColumn($courseid, $Trigger_id);
	}
	updateTriggerInfo($triggerInfo);
	foreach ($arrayOfConditionInfo as $value) {
		updateTriggerCondition($value);
	}
}




// Save all btn
if (isset($_POST['allRecord'])) {
	$json = $_POST['allRecord'];
	$allRecord = json_decode($json);
	$arrayOfTriggerInfo = $allRecord->triggerRecord;
	$arrayOfConditionInfo = $allRecord->conditionRecord;
	foreach ($arrayOfTriggerInfo as $value) {
		updateTriggerInfo($value);
	}

	foreach ($arrayOfConditionInfo as $value) {
		updateTriggerCondition($value);
	}
}
