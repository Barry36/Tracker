<?php
// This file ONLY gets the last Trigger Id from mdl_trigger_message table
require ('lib.php');
require ('query.php');

 
if (isset($_POST['lastTriggerId'])) {
	getLastTriggerId();
}
