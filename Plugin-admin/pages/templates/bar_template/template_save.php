<?php

    $DB = mysqli_connect('localhost','root','wjq123','beanstalk_moodle');

// Save changes when clicking Save Bar Template Starts
    $name = (isset($_POST['template']) ? $_POST['template'] : null);
    $title = (isset($_POST['title']) ? $_POST['title'] : null);
    $content = (isset($_POST['content']) ? $_POST['content'] : null);
    $newName = (isset($_POST['newName']) ? $_POST['newName'] : null);
    $btnContent = (isset($_POST['btnContent']) ? $_POST['btnContent'] : null);
    $mdlHeader = (isset($_POST['mdlHeader']) ? $_POST['mdlHeader'] : null);
    $mdlDesc = (isset($_POST['mdlDesc']) ? $_POST['mdlDesc'] : null);
    $promoCode = (isset($_POST['promoCode']) ? $_POST['promoCode'] : null);
    $parmaTracker = (isset($_POST['parmaTracker']) ? $_POST['parmaTracker'] : null);
    $promo_url = (isset($_POST['promo_url']) ? $_POST['promo_url'] : null); 
    
    echo "$promo_url";
    if ($name && $content && $newName && $title && $btnContent && $mdlHeader && $mdlDesc && $promoCode && $parmaTracker && $promo_url) {
        if (save_bar_template($name, $content, $newName, $title, $btnContent, $mdlHeader, $mdlDesc, $promoCode, $parmaTracker, $promo_url)) {
            echo "Saved {$newName}";
        }
    }
// Save changes when clicking Save Bar Template Ends



// Commit Code Changes For Dev Mode Starts
    $dev_original_name = (isset($_POST['dev_original_name']) ? $_POST['dev_original_name'] : null); 
    $dev_content = (isset($_POST['dev_content']) ? $_POST['dev_content'] : null); 
    if ($dev_original_name && $dev_content) {
        if (commit_code_changes($dev_original_name, $dev_content)) {
            echo "Saved {$dev_original_name}";
        }
    }
// Commit Code Changes For Dev Mode Ends
    
    

    function save_bar_template($name,$content,$newName,$title,$btnContent,$mdlHeader,$mdlDesc,$promoCode,$parmaTracker,$promo_url) {
        global $DB;
        if ($DB->connect_errno){
            echo "Failed to connect to MySQL: (" . $DB->connect_errno .") " . $DB->connect_error;
            return false;
        }
        if (!($stmt = $DB->prepare("UPDATE mdl_trigger_bar SET Bar_content=?, Bar_name=?, Bar_title=?, Bar_btn_content=?, Bar_mdl_header=?, 
            Bar_mdl_description=?, Bar_promo_code=?, Prama_tracker=?, promo_url=? WHERE Bar_name=?"))) {
            echo "Prepare failed ";
            $DB->close();
            return false;
        }
        if (!$stmt->bind_param("ssssssssss",$content,$newName,$title,$btnContent,$mdlHeader,$mdlDesc,$promoCode,$parmaTracker,$promo_url,$name)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
        $stmt->close();
        return true;
    }

    function commit_code_changes($dev_original_name, $dev_content){
        global $DB;
        if ($DB->connect_errno){
            echo "Failed to connect to MySQL: (" . $DB->connect_errno .") " . $DB->connect_error;
            return false;
        }
        if (!($stmt = $DB->prepare("UPDATE mdl_trigger_bar SET Bar_content=? WHERE Bar_name=?"))) {
            echo "Prepare failed ";
            $DB->close();
            return false;
        }
        if (!$stmt->bind_param("ss",$dev_content,$dev_original_name)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            return false;
        }
        $stmt->close();
        return true;
    }
?>
