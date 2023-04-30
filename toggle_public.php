<?php 
    include "recipe_connection.php";
    include "recipe_tools.php";
    if (isset($_POST['public'])) {
        $public = explode(",", $_POST['public'])[0];
        if ($public == 0) {
            $public = 1;
        } else {
            $public = 0;
        }
        $name = explode(",", $_POST['public'])[1];
        $sql = "UPDATE recipes SET public = (?) WHERE name = '$name'";
        sql_query($conn, $sql, 'i', [$public]);
        header("Location: my_recipe.php");
    } 
?>