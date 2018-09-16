<?php 
abstract class parameter{
	var $userid;
	function __construct($userid) {
		$this->userid = $userid;
    	echo 'Base __construct<br/>';
	}	
    abstract protected function getValue();
    // abstract protected function callProtectedFxns($fxn_Name);

    //abstract public function getOperator();
}
