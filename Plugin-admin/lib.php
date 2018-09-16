<?php

// *********************************************************************************************************************************
// *********************************************************************************************************************************
// ***************                      The file contains the library for admin control panel app                    ***************
// *********************************************************************************************************************************
// *********************************************************************************************************************************

require ('parameter/parameter.php');
require ('operator/operator.php');
foreach (glob(dirname(__FILE__).'/parameter/*.php') as $filename) {
       if (!strpos($filename, 'parameter.php')) {
            require_once($filename);
        }
    }

foreach (glob(dirname(__FILE__).'/operator/*.php') as $filename) {
       if (!strpos($filename, 'operator.php')) {
            require_once($filename);
        }
    }


function test($userid){
     global $DB;
    // 1. Get all the triggers that display bars
    

    // 2. Get all the conditions param's, operator's and condition-value's for each of the triggers


    // 3. Get actual param value as per userid
        // Notice: "userid" is a string, "days_to_be_expired" is an arr of obj, containing multiple courseid, coursename, days to be expired
        // "courseid" is an arr of obj, containing courseid and coursename, "time used quiz" should specify attempts of each course
     // This can get the actual value for given param's
        $obj1 = new userid($userid);
        $obj2 = new time_used_quiz($userid);
        $obj3 = new days_to_be_expired($userid);
        $obj4 = new courseid($userid);

        $UID = $obj1->callgetValue();
        $quiz_use_frequency = $obj2->callgetValue();
        $days_to_expiry = $obj3->callgetValue();
        $courseid = $obj4->callgetValue();
        
        // $arr = array();
        // $parameter = "courseid";
        // foreach ($paramArr as $value) {
        //     $tmp = $value->courseid;
        //     array_push($arr, $tmp);
        // }
        print_r($quiz_use_frequency);
}

// *******************************************************************************************
// ***************                      Display Bar                            ***************
// *******************************************************************************************
function displayBar($userid){

    // TriggerIdArr is all the triggers that display bars that met the conditions
    $TriggerIdArr = getTriggerId($userid);
    print_r($TriggerIdArr);
    echo "<br>";
    // for($i = 0; $i<sizeof($TriggerIdArr); $i++){
    //     $Trigger_id = $TriggerIdArr[$i];
    //     echo "Trigger_id is: $Trigger_id \n";
    //     // displayContent($Trigger_id,$userid);
    // }


    test($userid);
}


// *********************************************************************************************
// ***************                      Get Trigger ID                           ***************
// *********************************************************************************************
// *********************************************************************************************
// **********       getTriggerId($userid) returns all the trigger id's           ***************
// **********       that both display Bar and met conditions                     ***************
// *********************************************************************************************
function getTriggerId($userid){
    global $DB;
    $sql = ("
                SELECT Trigger_id
                FROM `mdl_trigger_message` 
                WHERE Message_type = ?
            ");
    $result = $DB->get_records_sql($sql, array("bar"));
    $Display_Bar_Array = array();

    // Loop thru all triggers and find those that display Bars
    // $Display_Bar_Array contains all the trigger id's that both display Bars and met all the conditions
    foreach ($result as $value) {
        $Trigger_id = $value->trigger_id;

    // Check conditions for each triggers. return true if all conditions are met, false otherwise
        echo "123 <br>";
        $result = checkConditions($Trigger_id, $userid);
        if ($result) {
            array_push($Display_Bar_Array,$Trigger_id);
        }
    }
    return $Display_Bar_Array;
}


// ***********************************************************************************************
// ***************                      Display Content                            ***************
// ***********************************************************************************************
function displayContent($Trigger_id,$userid){
    global $DB;

    $sql = ("
                SELECT bar_id
                FROM `mdl_trigger_bar_assignment` 
                WHERE trigger_id = ?
            ");
    $result = $DB->get_records_sql($sql, array($Trigger_id));
    foreach ($result as $value) {
        $bar_id = $value->bar_id;
        $sql = ("
                SELECT *
                FROM `mdl_trigger_bar` 
                WHERE ID = ?
            ");
        $tmp = $DB->get_records_sql($sql, array($bar_id));
        foreach ($tmp as $value) {
            $Bar_content = $value->bar_content;
            $Bar_title = $value->bar_title;
            $Bar_btnContent = $value->bar_btn_content;
            $Bar_mdlHeader = $value->bar_mdl_header;
            $Bar_mdlDesc = $value->bar_mdl_description;
            $Bar_promo_code = $value->bar_promo_code;
            $Bar_promolink = $value->promo_url;
        }
        echo $Bar_content."<br>";
        getCustomization($userid,$Bar_title,$Bar_btnContent,$Bar_mdlHeader,$Bar_mdlDesc,$Bar_promolink,$Bar_promo_code);
    }
}




function getCourseCompareRes($parameter,$targetVal, $userid){
    $arr = array();
    if ($parameter == 'courseid') {
        $obj = new courseid($userid);
        $paramArr = $obj->callgetValue();
        foreach ($paramArr as $value) {
            $tmp = $value->courseid;
            array_push($arr, $tmp);
        }
    }elseif ($parameter == 'coursename') {
        $obj = new coursename($userid);
        $paramArr = $obj->callgetValue();
        foreach ($paramArr as $value) {
            $tmp = $value->coursename;
            array_push($arr, $tmp);
        }
    }    
    if(in_array("$targetVal", $arr)){
        return true;
    }
}



function getCourseParamCompareRes($courseIDExisted, $parameter, $userid, $Trigger_id, $operator, $targetVal){
    // Get all courseid that user is enrolled in
    $obj = new courseid($userid);
    $currentCourseidArr = $obj->callgetValue();

    // checkCourseIDExisted() returns 1 if specify courseid
    // Otherwise returns -1 and then compare the days_to_be_expired for all courses that user is enrolled in
    // Only return TURE if all the courses met the condition, FALSE otherwise 
    if ($courseIDExisted == "1") {
        // Get target course info that was set from the Admin Panel
        $targetCourseInfo = getExistedCourseInfo($Trigger_id);
        $targetCourseId = $targetCourseInfo->courseid;
        $targetCourseOperator = $targetCourseInfo->operator;
        if ($targetCourseOperator == "Equal") {
            $setCourseId = 0;
            // Get condition result for the target courseid
            foreach ($currentCourseidArr as $value) {
                $currentCourseid = $value->courseid;
                if ($currentCourseid == $targetCourseId) {
                    $setCourseId = 1;

                    // getCurrentParamValue returns negative value when the course does not $parameter 
                    $currentVal = getCurrentParamValue("$parameter", $userid, $currentCourseid);
                    if ($currentVal < 0) {
                        return false;
                    }
                    $compareRes = getConditionRes($currentVal,$targetVal,$operator);
                }
            }

            // User is not enrolled in target course if $setCourseId == 0
            if ($setCourseId == 0 || !$compareRes) {
                
                return false;
            }
        }elseif ($targetCourseOperator == "Less_than") {
            $hasCourseIdMetCondition = 0;
            foreach ($currentCourseidArr as $value) {
                $currentCourseid = $value->courseid;
                if ($currentCourseid < $targetCourseId) {
                    $hasCourseIdMetCondition = 1;

                    // getCurrentParamValue returns negative value when the course does not $parameter 
                    $currentVal = getCurrentParamValue("$parameter", $userid, $currentCourseid);
                    if ($currentVal < 0) {
                        return false;
                    }
                    $compareRes = getConditionRes($currentVal,$targetVal,$operator);
                }
                echo "You wo de shi er? <br>";
                if (!$compareRes) {
                    return false;
                }
            }
            if ($hasCourseIdMetCondition == 0) {
                return false;
            }
        }elseif ($targetCourseOperator == "Greater_than") {
            $hasCourseIdMetCondition = 0;
            foreach ($currentCourseidArr as $value) {
                $currentCourseid = $value->courseid;
                if ($currentCourseid > $targetCourseId) {
                    $hasCourseIdMetCondition = 1;

                    // getCurrentParamValue returns negative value when the course does not $parameter 
                    $currentVal = getCurrentParamValue("$parameter", $userid, $currentCourseid);
                    if ($currentVal < 0) {
                        return false;
                    }
                    $compareRes = getConditionRes($currentVal,$targetVal,$operator);
                }
                if (!$compareRes && $hasCourseIdMetCondition = 1) {
                    return false;
                }
            }
            if ($hasCourseIdMetCondition == 0) {
                return false;
            }
        }
    }elseif ($courseIDExisted == "-1") {
        foreach ($currentCourseidArr as $value) {
            $currentCourseid = $value->courseid;
            // getCurrentParamValue returns negative value when the course does not $parameter 
            // Ignore those courses that don't have $paramter as parameter
            $currentVal = getCurrentParamValue("$parameter", $userid, $currentCourseid);
            if ($currentVal > 0) {
                $compareRes = getConditionRes($currentVal,$targetVal,$operator);
                if (!$compareRes) {
                    echo "$currentCourseid called twice!<br>";
                    return false;
                }
            }
        }
    }else{
        // $courseIDExisted value is invalid
        return false;
    }
    return true;
}


function getCurrentParamValue($parameter, $userid, $courseid){
    if ($parameter == 'time_used_quiz') {
        $courseHasQuiz = 0;
        $obj = new time_used_quiz($userid);
        $tmp = $obj->callgetValue();
        foreach ($tmp as $value) {
            if ($value->courseid == $courseid) {
                $courseHasQuiz = 1;
                $result = intval($value->attempt);
            }
        }
        // Course doesn't have time_used_quiz as parameter
        if ($courseHasQuiz == 0) {
            $result = -100;
        }

    }elseif ($parameter == 'days_to_be_expired') {
        $courseHasExpiryDay = 0;
        $obj = new days_to_be_expired($userid);
        $tmp = $obj->callgetValue();
        foreach ($tmp as $value) {
            if ($value->courseid == $courseid) {
                $courseNotHaveParam = 1;
                $result = floatval($value->daysleft);
            }
        }

        // Course doesn't have days_to_be_expired as parameter
        if ($courseHasExpiryDay == 0) {
            $result = -200;
        }
    }
    return $result;
}



function getConditionRes($currValue,$targetVal,$operator){
    if ($operator == "Equal") {
        $obj = new equal($userid);
    }elseif ($operator == "Less_than") {
        $obj = new lessthan($userid);
    }elseif ($operator == "Greater_than") {
        $obj = new greaterthan($userid);
    }

    $result = $obj->operation($currValue, $targetVal);
    return $result;
}


// getCourseId function gets the courseid from user's input. 
// Fucntion returns the courseid spcified by the user, returns -1, if doesn't specify the courseid
function checkCourseIDExisted($userid, $Trigger_id)
{
    global $DB;
    $sql = ("
                SELECT CourseIDExisted
                FROM `mdl_trigger_message` 
                WHERE Trigger_id = ?
            ");
    if (!$DB->get_records_sql($sql, array("$Trigger_id"))) {
        die("Error occurred when getting CourseIDExisted from mdl_trigger_message");
    }else{
        $result = $DB->get_records_sql($sql, array("$Trigger_id"));
    }
    foreach ($result as $value) {
        $CourseIDExisted = $value->courseidexisted;
    }
    return $CourseIDExisted;
}

function getExistedCourseInfo($Trigger_id){
    global $DB;
    $obj = new stdClass;
    $sql = ("
                SELECT value, operator
                FROM `mdl_trigger_condition`
                WHERE parameter = 'courseid'
                AND Trigger_id = ?
            ");
    if(!$DB->get_records_sql($sql, array("$Trigger_id"))){
        die("Error occurred when getting courseid from mdl_trigger_message");
    }else{
        $result = $DB->get_records_sql($sql, array("$Trigger_id"));
        foreach ($result as $value) {
            $courseid = $value->value;
            $courseid = intval($courseid);
            $operator = $value->operator;        
            $obj->courseid = $courseid;
            $obj->operator = $operator;
        }
    }
    return $obj;
}





// ***********************************************************************************************
// ***************                      Get Customization                          ***************
// ***********************************************************************************************
function getCustomization($userid,$Bar_title,$Bar_btnContent,$Bar_mdlHeader,$Bar_mdlDesc,$Bar_promolink,$Bar_promo_code){
    $str = '<script>
                $(document).ready(function(){
                    var str = "'.$Bar_promolink.'";
                    var res = str.replace("{$USERID}", "'.$userid.'");
                    $("#promolink").val(res);
                    
                    twUrl = twUrl.replace("{$CODE}", "'.$Bar_promo_code.'");
                    twUrl = twUrl.replace("{$USERID}", "'.$userid.'");

                    fbUrl = fbUrl.replace("{$CODE}", "'.$Bar_promo_code.'");
                    fbUrl = fbUrl.replace("{$USERID}", "'.$userid.'");

                    gmailUrl_global = gmailUrl_global.replace("{$CODE}", "'.$Bar_promo_code.'");
                    gmailUrl_global = gmailUrl_global.replace("{$USERID}", "'.$userid.'");

                    // outlookUrl = outlookUrl.replace("{$CODE}", "'.$Bar_promo_code.'");
                    // outlookUrl = outlookUrl.replace("{$USERID}", "'.$userid.'");

                    document.getElementById("bar_title").innerHTML = "'.$Bar_title.'";
                    $("#bar_btn").html("'.$Bar_btnContent.'");
                    document.getElementById("mdl_header").innerHTML = "'.$Bar_mdlHeader.'";
                    document.getElementById("modal_description").innerHTML = "'.$Bar_mdlDesc.'";       
                });
            </script>';
    echo $str;
}



// ***********************************************************************************************
// ***************                     Check Conditions                            ***************
// ***********************************************************************************************
function checkConditions($Trigger_id, $userid){

    // Get all conditions of the trigger
    global $DB;
    $sql = ("
                SELECT Parameter, Operator, Value
                FROM `mdl_trigger_condition` 
                WHERE Trigger_id = ?
            ");
    $record = $DB->get_records_sql($sql, array($Trigger_id));

    // checkCourseIDExisted() returns 1 if specify courseid, otherwise returns -1
    $courseIDExisted = checkCourseIDExisted($userid, $Trigger_id);

    // Check and get courseid for other conditions 
    foreach ($record as $value) {
        $parameter = $value->parameter;
        $operator = $value->operator;
        $targetVal = $value->value;

        if ($parameter == 'userid') {
            $obj = new userid($userid);
            $useridArr = $obj->callgetValue();
            foreach($useridArr as $tmp){
                $currValue = $tmp->userid;
                $result = getConditionRes($currValue,$targetVal,$operator);
            }
            if (!$result) {
                return false;
            }
        }elseif ($parameter == 'courseid' || $parameter == 'coursename') {
            $result = getCourseCompareRes($parameter,$targetVal, $userid);
            if(!$result){
                return false;
            }
        }elseif ($parameter == 'days_to_be_expired' || $parameter == 'time_used_quiz') {
            $result = getCourseParamCompareRes($courseIDExisted, $parameter, $userid, $Trigger_id, $operator, $targetVal);
            if(!$result){
                return false;
            }
        }
    }
    return true;
}






// getInfo() is used to retrieve and display data from DB to the admin person

function getInfo(){
    global $DB;
    $sql = "SELECT * FROM mdl_trigger_message";
    $result = $DB->query($sql);

    $var = $DB->query("SELECT Trigger_id FROM mdl_trigger_message ORDER BY Trigger_id DESC LIMIT 1");
    while ($row = $var->fetch_assoc()){
        $latestTriggerId = $row['Trigger_id'];
    } 
    
    $output = '
    <div id="table" class="table-editable" lastTriggerId="'.$latestTriggerId.'" style="padding: 3rem;">
    <button type="button" class="saveAllbtn btn btn-success" id="saveAllbtn">Save All</button>
    
    <table class="table table-bordered table-responsive-md text-center" id="outer-table">
    <tbody class="mdl-shadow--4dp">
      <tr style="background: #e9e9e9a6;">
        <th style="">Id</th>
        <th style="width: 21%;">Trigger Name</th>
        <th style="width: 16%";>Message Type</th>
        <th style="width: 18%";>Message Name</th>
        <th style="text-align: center; width: 31%">Conditions</th>
        <th style="width: 14%;"></th>
        <th><span class="table-add tooltip glyphicon glyphicon-plus" style="clear:both; position:relative;z-index: 1;"><a class="text-success">
            <i class="fa fa-plus fa-2x"></i></a></span></th>
      </tr>';
    while ($row = $result->fetch_assoc()) 
    {
        $Trigger_id = $row['Trigger_id'];
        $Trigger_name = $row['Trigger_name'];
        $Message_type = $row['Message_type'];
        $Message_name = $row['Message_name']; 
        
        // Get Message Type from DB, using two functions, whose purposes are return 'selected' string to auto fill the Message Type pull down list
        $output.='<tr class="existedTrigger"><td class="trig_id">'.$Trigger_id.'</td>
        <td><input readonly type="text" id="trigger_name_'.$Trigger_id.'" name="Trigger_Name_'.$Trigger_id.'" value= "'.$Trigger_name.'" class="addId hide"/>
            <span id = "SpanTriggerName_'.$Trigger_id.'" class="displaySpan">'.$Trigger_name.'</span>
        </td>
        <td name="Trigger_Type_'.$Trigger_id.'">
                <span id = "SpanTriggerMsgType_'.$Trigger_id.'" class="displaySpan">'.$Message_type.'</span>
                    <select disabled="true" class="msg_type hide" id="trigger_type_'.$Trigger_id.'" name="Trigger_Type" value="'.$Message_type.'">
                        <option value="bar">Bar</option>
                        <option value="email">Email</option>
                    </select>
        </td>
        <td name="Message_Content_'.$Trigger_id.'">
            <span  id = "SpanTriggerMsgName_'.$Trigger_id.'" class="displaySpan">'.$Message_name.'</span>
                <select disabled="true" class="msg_content hide" id="message_content_'.$Trigger_id.'" name="Message_Content">';

        if ($Message_type == 'bar') {
            // Get all the Bar Names from mdl_trigger_bar
            $Bar_names = get_bar_names();
            foreach ($Bar_names as $value) {
                $Bar_name = $value['Bar_name'];
                $output.= '<option value="'.$Bar_name.'">'.$Bar_name.'</option>';
            }
        }elseif ($Message_type == 'email') {
            $output .= '
                        <option value="quiz_remder">Quiz Function Reminder</option>
                        <option value="expire_remder">Expire Reminder</option>';
        }
        
        $output .= '
                </select>
            </td>';


        $output.='<td><div class="container-cdt-table " id="condition-table_'.$Trigger_id.'" >
                            <table class="table table-bordered table-responsive-md table-conditions text-center" id="inner-table_'.$Trigger_id.'">
                            <tbody class="mdl-shadow--4dp">
                            <tr style="background: #e9e9e9a6;">
                                <th style="  text-align: center;padding-right:0px;"><span class="condition-icon condition-add glyphicon glyphicon-plus hide"><a class="text-success"><i class="fa fa-plus"></i></a></span></th>
                                <th>Parameter</th>
                                <th>Operator</th>
                                <th style="width: 17%;">Value</th>
                                <th class="hide">ID</th`>
                            </tr>';
        // Get all conditions for each of the triggers
        $sql = "SELECT * FROM mdl_trigger_condition WHERE Trigger_id = $Trigger_id";
        $conditions = $DB->query($sql);
        $condition_num = 1;
        while ($condition_row = $conditions->fetch_assoc()) 
        {
            $condition_id = $condition_row['ID'];
            $Parameter = $condition_row['Parameter'];
            $Operator = $condition_row['Operator'];
            $Condition_Value = $condition_row['Value'];

            $output.= '        
                                  <tr style="background:white;">
                                      <td style="text-align: center; padding-right:0px;"><span class="condition-icon condition-minus glyphicon glyphicon-minus hide"><a class="text-success"><i class="fa fa-minus"></i></a></span></td>
                                      <td><select disabled="true" id="trigger_'.$Trigger_id.'-parameter_'.$condition_num.'" name="Parameter" class="hide condition-param">
                                            <option value="userid">user_id</option>
                                            <option value="courseid">courseid</option>
                                            <option value="days_to_be_expired">days_to_be_expired</option>
                                            <option value="time_used_quiz">time_used_quiz</option>
                                          </select>
                                          <span class="displaySpan displaySpan-param">'.$Parameter.'</span>
                                      </td>
                                      <td><select disabled="true" id="trigger_'.$Trigger_id.'-operator_'.$condition_num.'" name="Operator" class="condition-operator hide">
                                            <option value="Equal">Equal</option>
                                            <option value="Less_than">Less than</option>
                                            <option value="Greater_than">Greater than</option>
                                           </select>
                                           <span class="displaySpan displaySpan-operator">'.$Operator.'</span>
                                       </td>
                                      <td><input readonly type="text" id="trigger_'.$Trigger_id.'-condition_value_'.$condition_num.'" name="Condition_Value" class="condition-value hide" style="width: 75%;">
                                          <span class="displaySpan displaySpan-paramValue">'.$Condition_Value.'</span>
                                      </td>
                                      <td class="condt_id hide">'.$condition_id.'</td>'
                                      ;
            $condition_num++;
        }

        $output.='</tr></tbody></table></div>
                    <a class="hide viewCourseID" data-toggle="modal" data-target="#courseid_name_view_mdl" id="viewcourseidbtn_'.$Trigger_id.'" style="float: right;text-decoration: underline;color: #1e88e5;
                    ">View Course ID Chart</a>
            </td>';
        $output.='<td><button type="button" class="saveTriggerRow hide btn btn-success">Save</button>
            <button type="button" class="EditTrigger btn lighten-2 ">Edit</button>
        </td>
        <td style="padding-left: 0px;padding-right: 0px;">
          <span class="table-remove glyphicon glyphicon-remove"><a class="text-success"><i class="fa fa-remove fa-2x" style="color: red;"></i></a></span>
        </td></tr>';

    }
    //$DB->close();
    return $output;
}



function getNewRow(){
    global $DB;
    $newRow = '
        <tr class="table-outer hide">
        <td class="trig_id"></td>
        <td><input type="text" class="addId" value="New Trigger">
            <span class="displaySpan hide" id = "displayTriggerName">New Trigger</span>
        </td>
        <td><select class="msg_type" id="trigger_type">
                <option value="bar">Bar</option>
                <option value="email">Email</option>
            </select>
            <span class="displaySpan hide" id = "displayMsgType">bar</span>
        </td>
        <td><select class="msg_content" id="message_content">';
    $Bar_names = get_bar_names();
    foreach ($Bar_names as $value) {
      $Bar_name = $value['Bar_name'];
      $newRow.= '<option value="'.$Bar_name.'">'.$Bar_name.'</option>';
    }
        
    $newRow .= '</select>
            <span class="displaySpan hide" id="displayMsgContent">'.$Bar_name.'</span>
        </td>
        <td>
          <div class="container-cdt-table" id="condition-table">
            <table class="table table-bordered table-responsive-md table-conditions text-center" id="inner-table">
                  <tr style="background: #e9e9e9a6;">
                    <th style="text-align: center; padding-right: 0px;"><span class="condition-icon condition-add glyphicon glyphicon-plus"><a class="text-success"><i class="fa fa-plus"></i></a></span></th>
                    <th>Parameter</th>
                    <th>Operator</th>
                    <th style="width: 17%;">Value</th>
                    <th class="hide">Condition ID</th>
                  </tr>
                  <tr style="background:white;">
                      <td style="text-align: center; padding-right: 0px;"><span class="condition-icon condition-minus glyphicon glyphicon-minus"><a class="text-success"><i class="fa fa-minus" style="color:red;"></i></a></span></td>
                      <td><select  class="findParamInTrigger">
                            <option value="userid">user_id</option>
                            <option value="courseid">courseid</option>
                            <option value="days_to_be_expired">days_to_be_expired</option>
                            <option value="time_used_quiz">time_used_quiz</option>
                          </select>
                          <span class="displaySpan displaySpan-param hide">userid</span>
                      </td>
                      <td><select class="findOperatorInTrigger">
                            <option value="Equal">Equal</option>
                            <option value="Less_than">Less than</option>
                            <option value="Greater_than">Greater than</option>
                          </select>
                          <span class="displaySpan displaySpan-operator hide">Equal</span>
                       </td>
                      <td>
                        <input type="text" class="findValueInTrigger" value="1" style="width: 75%;">
                        <span class="displaySpan displaySpan-paramValue hide">1</span>
                      </td>
                      <td class="condt_id hide"></td>
                 </tr>
            </table>
          </div>
        </td>
        <td><button type="button" class="saveTriggerRow btn btn-success">Save</button>
            <button type="button" class="EditTrigger hide btn">Edit</button>
        </td>
        <td style=" padding-right: 0px; padding-left: 0px;">
          <span class="table-remove glyphicon glyphicon-remove"><a class="text-success"><i class="fa fa-remove fa-2x" style="color: red;"></i></a></span>
        </td>
      </tr>';

    return $newRow;
}


function getCourseidModal(){
    global $DB;
    $output = '
        <div class="modal fade" id="courseid_name_view_mdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document" style="max-width: 800px;">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Course ID</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <table class="table table-striped table-view-course-id">
                  <thead>
                    <tr>
                      <th scope="col" style="width: 15%;">Course ID</th>
                      <th scope="col" style="width: 30%;">Course Name</th>
                      <th scope="col" style="width: 30%;">Has Quiz</th>
                    </tr>
                  </thead>
                  <tbody>';

    $sql ="SELECT id, fullname FROM `mdl_course` ";
    $result = $DB->query($sql);

    while ($row = $result->fetch_assoc()){
      $mdl_courseid = $row['id'];
      $mdl_coursename = $row['fullname'];
      $output .= '
          <tr class="tr-view-course-id">
            <td class="td-view-course-id">'.$mdl_courseid.'</td>
            <td class="td-view-course-id">'.$mdl_coursename.'</td>
            <td class="td-view-course-id">'.(hasQuiz($mdl_courseid) ?'Yes':'No').'</td>
          </tr>';

    }


    $output .= '
                </tbody>
                  </table>
                  <div id="view_courseid_desc">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn" data-dismiss="modal" style="background-color: #a5a5a5;">Close</button>
                </div>
              </div>
            </div>
          </div>';
    return $output;
}

function hasQuiz($mdl_courseid){
    global $DB;
    $sql ="SELECT course FROM `mdl_quiz` GROUP BY course ";
    $result = $DB->query($sql);
    $arr = array();

    while ($row = $result->fetch_assoc()){
      $courseid = $row['course'];
      array_push($arr, $courseid);
  }
  
  $res = in_array($mdl_courseid, $arr);
  return $res;
}





function get_bar_names(){
    global $DB;
    $sql = "SELECT Bar_name FROM mdl_trigger_bar";
    if (!$result = $DB->query($sql)) {
        die ("Could not get the templates. MySQL Error [" . $DB->error . "]");
    }
    while ($row = $result->fetch_assoc()) {
        $Bar_name[] = $row;
    }
    return $Bar_name;
}

