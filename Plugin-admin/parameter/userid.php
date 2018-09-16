<?php

class userid extends parameter{
    public $userid;
    public function __construct($userid) {
        $this->userid = $userid;
       // echo 'userid __construct<br/>';
    }
    protected function getValue(){
        $obj = new stdClass;
        $obj->userid = $this->userid;
        $arr = array($obj);
        return $arr;
    }

    public function callgetValue(){
        return $this->getValue();
    }

}

