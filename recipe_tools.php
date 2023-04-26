<?php 
    function is_submitted($counter) {
        $check1 = isset(
            $_POST['name'],
            $_POST['pictureURL'],
            $_POST['directions'],
        );

        if (!$check1) {
            return false;
        }

        for ($i = 1; $i < $counter + 1; $i++) {
            if (!isset($_POST["ingredient$i"])) {
                return false;
            }
        }

        return true;
    }
    
    function santize_input($recipe) {
        // $_POST['name'],
        // $_POST['pictureURL'],
        // $_POST['ingredients'],
        // $_POST['directions'],
        if (!filter_var($recipe->get_pictureURL(), FILTER_VALIDATE_URL)) {
            return "Invalid URL";
        }

        return true;
    }
    
    function recipe_exists($db, $recipe_name) {
        foreach ($db as $data) {
            if ($data['name'] === $recipe_name) {
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

    function get_user_by_id($db, $username) {
        foreach ($db as $data) {
            if ($data['username'] === $username) {
                return $data['id'];
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