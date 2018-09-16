<?php
// Create New Email template

    $DB = mysqli_connect('localhost','root','wjq123','beanstalk_moodle');
    $name = (isset($_POST['template']) ? $_POST['template'] : null);
    


    echo "I am called";
    if ($name) {
        if (create_email_template($name)) {
            echo "Created {$name}";
        }
    }

    function create_email_template($name) {
        global $DB;
        if ($DB->connect_errno){
            echo "Failed to connect to MySQL: (" . $DB->connect_errno .") " . $DB->connect_error;
            return false;
        }
        if (!($stmt = $DB->prepare("INSERT INTO mdl_trigger_email (Template_name, Message_type, Email_subject, Email_body, Promo_code, Prama_tracker, Promo_url) 
        	VALUES (?,'email','','','','','')"))) {
            echo "Prepare failed";
            $DB->close();
            return false;
        }
        if (!$stmt->bind_param("s", $name)) {
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
        echo "Success!!<br>";
        return true;
    }
?>


