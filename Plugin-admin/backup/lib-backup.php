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




// *********************************************************************************************************************************
// ***************                      Display Bar Section                             ***************
// *********************************************************************************************************************************
function dispalyBar($userid){

    // TriggerIdArr is all the triggers that display bars that met the conditions
    $TriggerIdArr = getTriggerId($userid);
    for($i = 0; $i<sizeof($TriggerIdArr); $i++){
        $Trigger_id = $TriggerIdArr[$i];
        displayContent($Trigger_id);
    }
    echo "Yoooo";
    // sendBars($userid);
}
function displayContent($Trigger_id){
    global $DB;
    $sql = ("
                SELECT Bar_content
                FROM `mdl_trigger_bar` 
                WHERE Trigger_Id = ?
            ");
    $result = $DB->get_records_sql($sql, array($Trigger_id));
    foreach ($result as $value) {
        $Bar_content = $value->bar_content;
    }
    echo $Bar_content."<br>";
}


function getTriggerId($userid){
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

function checkConditions($Trigger_id, $userid){
    // Get all conditions of the trigger
    global $DB;
    $sql = ("
                SELECT Parameter, Operator, Value
                FROM `mdl_trigger_condition` 
                WHERE Trigger_id = ?
            ");
    $result = $DB->get_records_sql($sql, array($Trigger_id));
    $condtRes = array();
    foreach ($result as $value) {
        $parameter = $value->parameter;
        if ($parameter == 'courseid') {
            $obj = new courseid($userid);
            $tmp = $obj->callgetValue();
            $courseid = $tmp[0]->courseid;
        }
    }
    foreach ($result as $value) {
        $parameter = $value->parameter;
        $operator = $value->operator;
        $targetVal = $value->value;
        
        // $currValue is an array of objects
        if ($parameter != 'courseid') {
            $currValue = getCurrentParamValue($parameter, $userid, $courseid);
            $tmp = getConditionRes($currValue,$targetVal,$operator);
            if (!$tmp) {
                return false;
            }
            array_push($condtRes, $tmp);
        }
        
    }
    return true;
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
function sendBars($userid){
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

        $tmp1 = $obj1->callgetValue();
        $tmp2 = $obj2->callgetValue();
        $tmp3 = $obj3->callgetValue();
        $tmp4 = $obj4->callgetValue();

        print_r($tmp2);
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
    
    $output = '<div id="table" class="table-editable" lastTriggerId="'.$latestTriggerId.'" >
    
    <table class="table" id="outer-table">
      <form action="admin_panel_api.php" id="idForm" >
      <tbody id="trigger-table-tbody">
      <tr>
        <th style="">Id</th>
        <th style="width: 21%;">Trigger Name</th>
        <th style="width: 16%";>Message Type</th>
        <th style="width: 18%";>Message Name</th>
        <th style="text-align: center; width: 27%">Conditions</th>
        <th style="width: 18%;"><button type="button" class="saveAllbtn mdl-button mdl-js-button mdl-button--raised" id = "saveAllbtn">Save All</button></th>
        <th><span class="table-add tooltip glyphicon glyphicon-plus"></span></th>
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
                <select disabled="true" class="msg_content hide" id="message_content_'.$Trigger_id.'" name="Message_Content">
                </select>
            </td>';


        $output.='<td><div class="container-cdt-table" id="condition-table_'.$Trigger_id.'" >
                            <table class="table-conditions" id="inner-table_'.$Trigger_id.'">
                            <tr>
                                <th><span class="condition-icon condition-add glyphicon glyphicon-plus hide"></th>
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
                                  <tr>
                                      <td><span class="condition-icon condition-minus glyphicon glyphicon-minus hide"></span></td>
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
        $output.='</tr></table></div></td>';
        $output.='<td><button type="button" class="saveTriggerRow hide mdl-button mdl-js-button mdl-button--raised">Save</button>
            <button type="button" class="EditTrigger mdl-button mdl-js-button mdl-button--raised ">Edit</button>
        </td>
        <td style=" padding-right: 0px; text-align: right;">
          <span class="table-remove glyphicon glyphicon-remove"></span>
        </td></tr>';

    }
    $DB->close();
    return $output;
}


