<?php 
    include "recipe_connection.php";
    include "recipe_tools.php";
    if (isset($_POST['favorite'])) {
        $favorite = explode(",", $_POST['favorite'])[0];
        if ($favorite == 0) {
            $favorite = 1;
        } else {
            $favorite = 0;
        }
        $name = explode(",", $_POST['favorite'])[1];
        $sql = "UPDATE recipes SET favorite = (?) WHERE name = '$name'";
        sql_query($conn, $sql, 'i', [$favorite]);
        header("Location: my_recipe.php");
    } 
?>