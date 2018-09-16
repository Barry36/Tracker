<?php

// This file stores all the queries used to manipulate, retrieve data from DB
$DB = mysqli_connect('localhost', 'root','wjq123','beanstalk_moodle');



// *********************************************************************************************************************************
// ***************                                       Insert functions                                            ***************
// *********************************************************************************************************************************
function insertDefaultTrigger(){
    global $DB;

    // Insert Trigger 
    $sql  = "
                INSERT INTO mdl_trigger_message (Trigger_name, Message_type, Message_Name)
                VALUES  ('New Trigger', 'bar','Recommand to Friends')
            ";
    if ($DB->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }

    // Get trigger Id from mdl_trigger_message table
    $result = $DB->query("SELECT Trigger_id FROM mdl_trigger_message ORDER BY trigger_Id DESC limit 1");
    while ($row = $result->fetch_assoc()){
        $TriggerId = $row['Trigger_id'];
    }

    $sql = "
                INSERT INTO mdl_trigger_condition (trigger_id, Parameter, operator, value)
                VALUES ('$TriggerId','userid','Equal','1')
            ";
     if ($DB->query($sql) === TRUE) {
         $result = $DB->query("SELECT ID FROM mdl_trigger_condition ORDER BY ID DESC LIMIT 1");
         while ($row = $result->fetch_assoc()){
            $conditionId = $row['ID'];
         }    
        echo "$conditionId";
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }
}


function insertDefaultCondition($Trigger_Id){
    global $DB; 
    $sql  = "
                INSERT INTO mdl_trigger_condition (trigger_id, Parameter, operator, value)
                VALUES ('$Trigger_Id','userid','Equal','1')
            ";
    if ($DB->query($sql) === TRUE) {
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }
}


function getLatestConditionId(){
    global $DB; 
    $result = $DB->query("SELECT ID FROM mdl_trigger_condition ORDER BY ID DESC LIMIT 1");
    while ($row = $result->fetch_assoc()){
        $conditionId = $row['ID'];
    }    
    echo "$conditionId";
}



// *********************************************************************************************************************************
// ***************                                       Delete functions                                            ***************
// *********************************************************************************************************************************
function deleteTrigger($deleteTriggerInfo){
    global $DB;
    $deleteTriggerId = $deleteTriggerInfo->triggerId;
    $MsgType = $deleteTriggerInfo->msgType;
    $MsgName = $deleteTriggerInfo->msgName;
    // Delete from mdl_trigger_message table
    $BarId = getBarId($MsgName);
    $sql = "
            DELETE FROM mdl_trigger_message
            WHERE Trigger_id = '$deleteTriggerId'
            ";
    if ($DB->query($sql) === TRUE) {
        echo "Trigger has been DELETED from table mdl_trigger_message and"."Trigger ID is: ".$deleteTriggerId."\n";
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }


    // Delete from mdl_trigger_bar_assignment or mdl_trigger_email_assignment table
    if ($MsgType == 'bar') {
        $sql = "
                    DELETE FROM mdl_trigger_bar_assignment
                    WHERE trigger_id = '$deleteTriggerId' AND bar_id = '$BarId'
                ";
        if ($DB->query($sql) === TRUE) {
            echo "Trigger has been DELETED from table mdl_trigger_bar \n";
        } else {
            echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
        }
    }else if ($MsgType == 'email') {
        $sql = "
                    DELETE FROM mdl_trigger_email
                    WHERE Trigger_Id = '$deleteTriggerId'
                ";
        if ($DB->query($sql) === TRUE) {
            echo "Trigger has been DELETED from table mdl_trigger_email \n";
        } else {
            echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
        }
    }
    



    // Delete from mdl_trigger_condition
    $sql = "
            DELETE FROM mdl_trigger_condition
            WHERE Trigger_Id = '$deleteTriggerId'
            ";
    if ($DB->query($sql) === TRUE) {
        echo "Conditions have been DELETED from table mdl_trigger_condition ". "And the Trigger ID is: ".$deleteTriggerId."\n";
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }
}


function deleteCondition($deleteConditionId){
     global $DB;

    // Delete from mdl_trigger_condition table
    $sql = " 
            DELETE FROM mdl_trigger_condition
            WHERE ID = '$deleteConditionId'
            ";
    if ($DB->query($sql) === TRUE) {
        echo "Conditions have been DELETED from table mdl_trigger_condition ". "And the condition ID is: ".$deleteConditionId."\n";
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }
}




// *********************************************************************************************************************************
// ***************                                       Update functions                                            ***************
// *********************************************************************************************************************************

// Save and Save All button
function updateTriggerInfo($updateTriggerInfo){
    global $DB;
    $TriggerId = $updateTriggerInfo->triggerId;
    $TriggerName = $updateTriggerInfo->triggerName;
    $MsgType = $updateTriggerInfo->MsgType;
    $MsgName = $updateTriggerInfo->MsgName;
    $OriginalMsgName = $updateTriggerInfo->OriginalMsgName;
    
    $sql = "
                   UPDATE mdl_trigger_message
                   SET Trigger_name='$TriggerName', Message_type='$MsgType', Message_name='$MsgName'
                   WHERE Trigger_id='$TriggerId' 
                ";
    if ($DB->query($sql) === TRUE) {
        echo "Updated mdl_trigger_message successfully \n";
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }

    // Update mdl_trigger_bar_assignment 
    
    if ($MsgType == 'bar') {
        // get bar id as per MsgName
        $Original_Bar_id = getBarId($OriginalMsgName);
        $Current_Bar_id = getBarId($MsgName);
        // echo "OriginalMsgName is: ".$OriginalMsgName." And MsgName is: ".$MsgName;
        // echo "Original_Bar_id is: ".$Original_Bar_id." And Current_Bar_id is: ".$Current_Bar_id;
        // Update the current assignment if exists, otherwise create new assignment
        if (assignment_exist($TriggerId,$Original_Bar_id)) {
            updateTriggerBarAssignment($TriggerId, $Original_Bar_id, $Current_Bar_id);
        }else{
            createTriggerBarAssignment($TriggerId, $Current_Bar_id);
        }
        
    }
    // elseif ($MsgType == 'email') {
    //     $sql = "
    //                UPDATE mdl_trigger_email
    //                SET Email_name='$TriggerName'
    //                WHERE Trigger_Id='$TriggerId' 
    //             ";
    //     if (!$result = $DB->query($sql)) {
    //             die ("Could not update the Trigger Info. MySQL Error [" . $DB->error . "]");
    //         }
    //     }    

    

}

function updateCourseIDExistedColumn($courseid,$TriggerId){
    global $DB;
    $sql = "
                UPDATE mdl_trigger_message
                SET CourseIDExisted='$courseid'
                WHERE Trigger_id='$TriggerId'
            ";
    if ($DB->query($sql) === TRUE) {
        echo "Updated updateCourseIDExistedColumn successfully \n";
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }
}
function assignment_exist($TriggerId,$Original_Bar_id){
    global $DB;
    $sql = "
               SELECT * FROM mdl_trigger_bar_assignment
               WHERE Trigger_Id = '$TriggerId' AND bar_id = '$Original_Bar_id'
            ";
    if (!$result = $DB->query($sql)) {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }
    if ($result->num_rows == 0) {
        return false;
    }else{
        return true;
    }
}

function createTriggerBarAssignment($TriggerId,$Current_Bar_id){
    global $DB;
    $sql = "
           INSERT INTO mdl_trigger_bar_assignment (Trigger_Id, bar_id)
           VALUES ('$TriggerId', '$Current_Bar_id');
            ";
    if ($DB->query($sql) === TRUE) {
        echo "INSERTED INTO mdl_trigger_bar_assignment successfully \n";
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }
}

function updateTriggerBarAssignment($TriggerId,$Original_Bar_id,$Current_Bar_id){
    global $DB;
    $sql = "
               UPDATE mdl_trigger_bar_assignment
               SET bar_id='$Current_Bar_id'
               WHERE Trigger_Id = '$TriggerId' AND bar_id = '$Original_Bar_id'
            ";
    if ($DB->query($sql) === TRUE) {
        echo "Updated mdl_trigger_bar_assignment successfully \n";
    } else {
        echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
    }
}

function getBarId($MsgName){
    global $DB;
    $sql = "
                SELECT ID FROM mdl_trigger_bar 
                WHERE Bar_name = '$MsgName'
                ";
    if (!$result = $DB->query($sql)) {
       die ("Could not get the templates. MySQL Error [" . $DB->error . "]");
    }
    while ($row = $result->fetch_assoc()){
        $BarId = $row['ID'];
    }
    return $BarId;
}

function updateTriggerCondition($updateConditionInfo){
    global $DB;
    $conditionId = $updateConditionInfo->conditionId;
    $param = $updateConditionInfo->Param;
    $operator = $updateConditionInfo->Operator;
    $conditionValue = $updateConditionInfo->paramValue;
    $sql = "
                UPDATE mdl_trigger_condition 
                SET Parameter = '$param', operator = '$operator' ,value = '$conditionValue'
                WHERE ID = '$conditionId'
                ";
     if ($DB->query($sql) === TRUE) {
            echo "Condition has been UPDATED successfully "."Condition ID is: ".$conditionId."\n";
        } else {
            echo "Error: " . $sql . "&nbsp;" . $DB->error."\n";
        }                                      
}


// This function is only for test purposes
function clearAllRecords(){

}


// *********************************************************************************************************************************
// ***************                                       Select functions                                            ***************
// *********************************************************************************************************************************
function getLastTriggerId(){
    global $DB;
    $sql = "
            SELECT Trigger_id FROM mdl_trigger_message 
            ORDER BY Trigger_id DESC 
            LIMIT 1
            ";
    $result = $DB->query("SELECT Trigger_id FROM mdl_trigger_message ORDER BY trigger_Id DESC limit 1");
    while ($row = $result->fetch_assoc()){
        $TriggerId = $row['Trigger_id'];
    }
    echo "$TriggerId";       
}
