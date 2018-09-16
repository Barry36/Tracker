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


// ****************************************************************************************************
// ***************                      Display Bar Section                             ***************
// ****************************************************************************************************
function displayBar($userid){

    // BarTriggerIdArr is an array that contains all the trigger id's that both display Bars and met all the conditions
    $BarTriggerIdArr = getBarTriggerId($userid);

    for($i = 0; $i<sizeof($BarTriggerIdArr); $i++){
        $Trigger_id = $BarTriggerIdArr[$i];
        // echo "Trigger_id is: $Trigger_id \n";
        displayContent($Trigger_id,$userid);
    }
    // test($userid);
}


// ****************************************************************************************************
// ***************                    Get TriggerID Section                             ***************
// ****************************************************************************************************
function getBarTriggerId($userid){
    global $DB;
    $sql = ("
                SELECT Trigger_id
                FROM `mdl_trigger_message` 
                WHERE Message_type = ?
            ");
    $result = $DB->get_records_sql($sql, array("bar"));
    $Display_Bar_Array = array();

    // Use a foreach loop to loop thru all triggers and find those that display Bars
    // $Display_Bar_Array contains all the trigger id's that both display Bars and met all the conditions
    foreach ($result as $value) {
        $Trigger_id = $value->trigger_id;

    // Check conditions for each triggers. return true if all conditions are met, false otherwise
        $result = checkConditions($Trigger_id, $userid);
        if ($result) {
            array_push($Display_Bar_Array,$Trigger_id);
        }
    }
    return $Display_Bar_Array;
}


// ****************************************************************************************************
// ***************                    Check Conditions Section                          ***************
// ****************************************************************************************************
function checkConditions($Trigger_id, $userid){
    // Get all conditions of the trigger
    global $DB;
    $sql = ("
                SELECT Parameter, Operator, Value
                FROM `mdl_trigger_condition` 
                WHERE Trigger_id = ?
            ");
    $result = $DB->get_records_sql($sql, array($Trigger_id));

    $paramArr = array();
    $hasCourseIdCondition = false;
    $hasUserIdCondition = false;
    $courseIdExisted = false;
    // Check and get courseid for other conditions 
    foreach ($result as $value) {
        $parameter = $value->parameter;
        $operator = $value->operator;
        $targetVal = $value->value;
        array_push($paramArr, $parameter);

        if ($parameter == 'userid') {
            $hasUserIdCondition = true;
            // courseid is trivial for param "userid", to make it zero and pass it to getCurrentParamValue
            $courseid = 0;
        }elseif ($parameter == 'courseid') {
            $targetCourseId = $targetVal;
            $hasCourseIdCondition = true;
            $courseIdExisted = false;
            $obj = new courseid($userid);
            $courseidArr = $obj->callgetValue();

            // Check if target course id is in the user's courseid Arr
            // targetCourseId has only one value
            foreach($courseidArr as $tmp){
                if ( intval($tmp->courseid) == intval($targetCourseId)) {
                    $courseid = $targetCourseId;
                    $courseIdExisted = true;
                    break;
                }
            }
        }elseif ($parameter == 'coursename') {
            $targetCourseName = $targetVal;
        }
    }

    // No userid condition and user does not have course enrolled set in the trigger condition, should give error
    if (!$hasUserIdCondition && !$courseIdExisted) {
        return false;
    }elseif ($hasUserIdCondition && !$courseIdExisted && $hasCourseIdCondition) {
        return false;
    }else{
        // Two scenarioes, ONLY courseid and NOT only courseid

        // ONLY courseid and courseid is existed
        if (sizeof($paramArr) == 1 && $paramArr[0] == 'courseid' && $courseIdExisted) {
            return true;
        }
        // NOT only courseid as params, then deal with other parameters
        foreach ($result as $value) {
            $parameter = $value->parameter;
            $operator = $value->operator;
            $targetVal = $value->value;
            if($parameter != 'courseid'){
                // $currValue is an array of objects
                
                $currValue = getCurrentParamValue($parameter, $userid, $courseid);
                $tmp = getConditionRes($currValue,$targetVal,$operator);
                if (!$tmp) {
                    return false;
                } 
            }
                          
        }
        return true;
    }
    
}


// ****************************************************************************************************
// ***************                    Display Content Section                           ***************
// ****************************************************************************************************
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


function getCurrentParamValue($parameter, $userid, $courseid){
    if ($parameter == 'userid') {
        $obj = new userid($userid);
        $tmp = $obj->callgetValue();
        $result = $tmp[0]->userid;
    }elseif ($parameter == 'time_used_quiz') {
        $obj = new time_used_quiz($userid);
        $tmp = $obj->callgetValue();
        foreach ($tmp as $value) {
            if ($value->courseid == $courseid) {
                $result = intval($value->attempt);
            }
        }

    }elseif ($parameter == 'days_to_be_expired') {
        $obj = new days_to_be_expired($userid);
        $tmp = $obj->callgetValue();
        foreach ($tmp as $value) {
            if ($value->courseid == $courseid) {
                $result = floatval($value->daysleft);
            }
        }
    }elseif ($parameter == 'coursename') {
        # code...
    }
    return $result;
}

function getConditionRes($currValue,$targetVal,$operator){
    // check operator
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
                                            <option value="course_name">course name</option>
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
        $output.='</tr></tbody></table></div></td>';
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
        $obj5 = new coursename($userid);

        $UID = $obj1->callgetValue();
        $quiz_use_frequency = $obj2->callgetValue();
        $days_to_expiry = $obj3->callgetValue();
        $courseid = $obj4->callgetValue();

        print_r($quiz_use_frequency);
}
