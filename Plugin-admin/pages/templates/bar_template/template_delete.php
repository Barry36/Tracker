<?php

// This is for deleting bar template from DB
    $DB = mysqli_connect('localhost','root','wjq123','beanstalk_moodle');

    $name = (isset($_POST['template']) ? $_POST['template'] : null);
    if ($name) {
        if (delete_bar_template($name)) {
            echo "Deleted {$name}";
        }
    }

    function delete_bar_template($name) {
        global $DB;
        if ($DB->connect_errno){
            echo "Failed to connect to MySQL: (" . $DB->connect_errno .") " . $DB->connect_error;
            return false;
        }
        if (!($stmt = $DB->prepare("DELETE FROM mdl_trigger_bar WHERE Bar_name=? LIMIT 1"))) {
            echo "Prepare failed ";
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
        return true;
    }
