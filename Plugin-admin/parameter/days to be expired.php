<?php

class days_to_be_expired extends parameter{
    public $userid;
    public function __construct($userid) {
        $this->userid = $userid;
       // echo 'days_to_be_expired  __construct<br/>';
    }
    protected function getValue(){
        
        global $DB;
        $userid = $this->userid;
        $sql = ("
                    SELECT enrol.id, enrol.courseid, course.sortorder, course.fullname, user_enrol.timeend 
                    FROM `mdl_user_enrolments` user_enrol 
                    JOIN `mdl_enrol`enrol ON user_enrol.enrolid = enrol.id
                    JOIN `mdl_course`course ON enrol.courseid = course.id
                    WHERE userid = ?
    
                ");
        $result = $DB->get_records_sql($sql, array($userid));
        $arr = array();
        foreach($result as $value){
            $courseid = $value->courseid;
            $coursename = $value->fullname;
            $courseend = $value->timeend;
            $daysleft = ($courseend - time())/86400;

            $tmp = new stdClass;
            $tmp->courseid = $courseid;
            $tmp->coursename = $coursename;
            $tmp->daysleft = $daysleft;

            array_push($arr, $tmp);
        }

        return $arr; 
    }

    public function callgetValue(){
        return $this->getValue();
    }

}
