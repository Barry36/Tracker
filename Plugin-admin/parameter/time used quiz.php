<?php

class time_used_quiz extends parameter{
    public $userid;
    public $arr;
    public function __construct($userid) {
        $this->userid = $userid;
        $this->arr = array();
       // echo 'time_used_quiz  __construct<br/>';
    }
    protected function getValue(){
        global $DB;
        $userid = $this->userid;
        $sql =("
                SELECT quiz_attempts.id, SUM(quiz_attempts.attempt), quiz.course 
                FROM `mdl_quiz_attempts` quiz_attempts 
                JOIN `mdl_quiz` quiz ON quiz_attempts.quiz = quiz.id 
                WHERE userid = ? 
                GROUP BY quiz_attempts.quiz;

                ");
        $result = $DB->get_records_sql($sql, array($userid));
        $arr = $this->arr;
        $str = 'sum(quiz_attempts.attempt)';
        
        foreach ($result as $value) {
            $courseid = $value->course;
            if($this->couseidExist($courseid, $arr)){
                $arr[$index]->attempt = $arr[$index]->attempt + $value->$str;
            }else{
                    if (sizeof($arr) == 0) {
                        $tmp = new stdClass;
                        $tmp->courseid = $courseid;
                        $tmp->attempt = $value->$str;
                        array_push($arr, $tmp);
                        $index = 0;

                    }else{
                        $tmp = new stdClass;
                        $tmp->courseid = $courseid;
                        $tmp->attempt = $value->$str;
                        array_push($arr, $tmp);
                        $index++;
                    }
                }          
        }
        return $arr;
    }

    public function couseidExist($courseid, $arr){
        for($i = 0; $i < sizeof($arr); ++$i){
            if ($arr[$i]->courseid == $courseid) {
                return true;
            }    
        }
        return false;
    }
    public function callgetValue(){
        return $this->getValue();
    }


}
