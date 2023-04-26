<?php 
    function is_submitted() {
        return isset($_POST['username'], $_POST['password']);
    }
    
    function santize_input($username, $pwd) {
        if (strlen($pwd) < 8) {
            return "Password is too short";
        }
        if (!preg_match("#[0-9]+#", $pwd)) {
            return "Password must containe at least one number";
        }

        if (!preg_match("#[a-z]+#", $pwd)) {
            return "Password must contain at least one lowercase letter";
        }

        if (!preg_match("#[A-Z]+#", $pwd)) {
            return "Password must contain at least one uppercase letter";
        }

        if (!preg_match("#\W+#", $pwd)) {
            return "Password must contain at least one symbol";
        }

        return true;
    }
    
    function username_exists($db, $username) {
        foreach ($db as $data) {
            if ($data['username'] === $username) {
                return true;
            }
            
        }
        return false;
    }

    function get_user_by_name($db, $username) {
        foreach ($db as $data) {
            if ($data['username'] === $username) {
                return array($data['username'], $data['password']);
            }
            
        }
    }

    function sql_query($conn, $sql, $types, $placeholders) {
        $statement = $conn->prepare($sql);
        $statement->bind_param($types, ...$placeholders);
        $statement->execute();
        return $statement->get_result();
    }
?>