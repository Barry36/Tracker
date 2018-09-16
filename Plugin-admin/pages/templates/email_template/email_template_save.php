<?php 
// Save changes of an email template

    $DB = mysqli_connect('localhost','root','wjq123','beanstalk_moodle');

// Save changes when clicking Save Email Template Starts
    $name = (isset($_POST['template']) ? $_POST['template'] : null);
    $newName = (isset($_POST['newName']) ? $_POST['newName'] : null);
    $subject = (isset($_POST['subject']) ? $_POST['subject'] : null);
    $promoCode = (isset($_POST['promoCode']) ? $_POST['promoCode'] : null);
    $parmaTracker = (isset($_POST['parmaTracker']) ? $_POST['parmaTracker'] : null);
    $promo_url = (isset($_POST['promo_url']) ? $_POST['promo_url'] : null); 
    $body = (isset($_POST['body']) ? $_POST['body'] : null);
    

    if ($name && $newName) {
        if (save_email_template($name, $newName, $subject, $promoCode, $parmaTracker, $promo_url, $body)) {
            echo "Saved {$newName}";
        }
    }
// Save changes when clicking Save Email Template Ends

    
    function save_email_template($name, $newName, $subject, $promoCode, $parmaTracker, $promo_url, $body) {
        global $DB;
        if ($DB->connect_errno){
            echo "Failed to connect to MySQL: (" . $DB->connect_errno .") " . $DB->connect_error;
            return false;
        }
        if (!($stmt = $DB->prepare("UPDATE mdl_trigger_email SET Email_body=?, Template_name=?, Email_subject=?, Promo_code=?, Prama_tracker=?, Promo_url=? WHERE Template_name=?"))) {
            echo "Prepare failed ";
            $DB->close();
            return false;
        }
        if (!$stmt->bind_param("sssssss",$body,$newName,$subject,$promoCode,$parmaTracker,$promo_url,$name)) {
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
