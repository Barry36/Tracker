<?php

class courseid extends parameter{
    public $userid;
    public function __construct($userid) {
        $this->userid = $userid;
        // echo 'courseid  __construct<br/>';
    }
    protected function getValue(){
        global $DB;
        $userid = $this->userid;
        $sql = ("
                    SELECT enrol.courseid, course.fullname
                    FROM `mdl_user_enrolments` user_enrol 
                    JOIN `mdl_enrol`enrol ON user_enrol.enrolid = enrol.id
                    JOIN `mdl_course`course ON enrol.courseid = course.id
                    WHERE userid = ?
    
                ");
        $result = $DB->get_records_sql($sql, array($userid));
        $arr = array();
        foreach ($result as $value) {
            $obj = new stdClass;
            $obj->courseid = $value->courseid;
        	array_push($arr, $obj);
        }
        return $arr;
    }

    public function callgetValue(){
        return $this->getValue();
    }

}
`
