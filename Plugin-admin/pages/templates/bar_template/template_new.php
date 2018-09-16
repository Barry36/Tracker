<?php

    $DB = mysqli_connect('localhost','root','wjq123','beanstalk_moodle');
    $name = (isset($_POST['template']) ? $_POST['template'] : null);
    
    if ($name) {
        if (create_bar_template($name)) {
            echo "Created {$name}";
        }
    }

    function create_bar_template($name) {
        global $DB;
        if ($DB->connect_errno){
            echo "Failed to connect to MySQL: (" . $DB->connect_errno .") " . $DB->connect_error;
            return false;
        }
        if (!($stmt = $DB->prepare("INSERT INTO mdl_trigger_bar (Bar_name, Message_type, position, Bar_content, Bar_title, Bar_btn_content, Bar_mdl_header, Bar_mdl_description, Bar_promo_code, Prama_tracker, promo_url) VALUES (?,'bar','','','','','','','','','')"))) {
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

