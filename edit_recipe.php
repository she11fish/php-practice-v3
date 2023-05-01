<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Recipe Creator</title>
</head>

<body class="bg-light">
    <?php 
        include "navbar.php";
        include "recipe_connection.php";
        include "user_connection.php";
        include "recipe_tools.php";
        include "recipe.php";
    ?>
    <div class="container d-flex flex-column justify-content-center align-items-center">
        <div class='fs-1'>Edit Recipe</div>
        <form class="d-flex w-50 justify-content-evenly align-items-start" id="input_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div>
                <div class='fs-4'>Name</div>
                <input type="text" name="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''?>" readonly="readonly"/>
                <div class='fs-4'>Image URL</div>
                <input type="text" name="pictureURL" value="<?php echo isset($_POST['pictureURL']) ? $_POST['pictureURL'] : ''?>"/>
                <?php
                    $ingredient_counter = 5;
                    $direction_counter = 5;
                    if (isset($_POST['add_ingredient'])) {
                        $ingredient_counter = (int) explode(",", $_POST['add_ingredient'])[0];
                        $direction_counter = (int) explode(",", $_POST['add_ingredient'])[1];
                        $ingredient_counter++;
                    }
                    if (isset($_POST['remove_ingredient'])) {
                        $ingredient_counter = (int) explode(",", $_POST['remove_ingredient'])[0];
                        $direction_counter = (int) explode(",", $_POST['remove_ingredient'])[1];
                        $ingredient_counter--;
                    }
                    if (isset($_POST['add_direction'])) {
                        $direction_counter = (int) explode(",", $_POST['add_direction'])[0];
                        $ingredient_counter = (int) explode(",", $_POST['add_direction'])[1];
                        $direction_counter++;
                    }
                    if (isset($_POST['remove_direction'])) {
                        $direction_counter = (int) explode(",", $_POST['remove_direction'])[0];
                        $ingredient_counter = (int) explode(",", $_POST['remove_direction'])[1];
                        $direction_counter--;
                    }
                    if (isset($_POST['clicked'])) {
                        $ingredient_counter = (int) explode(",", $_POST['clicked'])[0];
                    }
                    if (isset($_POST['clicked'])) {
                        $direction_counter = (int) explode(",", $_POST['clicked'])[1];
                    }
                    for ($i = 1; $i < $ingredient_counter + 1; $i++) {
                        $ingredient_value = isset($_POST["ingredient$i"]) ? $_POST["ingredient$i"] : '';
                        echo "
                            <div class='fs-4'>Ingredient $i</div>
                            <input type='text' name='ingredient$i' value='$ingredient_value' />
                        ";
                    }
                    echo "
                        <div class='d-flex justify-content-evenly mt-2'>
                            <button type='submit' class='btn btn-primary' name='add_ingredient' value='$ingredient_counter,$direction_counter'>+</button>
                            <button type='submit' class='btn btn-primary' name='remove_ingredient' value='$ingredient_counter,$direction_counter'>-</button>
                        </div>
                    ";
                ?>
            </div>
            <div>
                <?php
                    for ($i = 1; $i < $direction_counter + 1; $i++) {
                        $direction_value = isset($_POST["direction$i"]) ? $_POST["direction$i"] : '';
                        echo "
                            <div class='fs-4'>Direction $i</div>
                            <input type='text' name='direction$i' value='$direction_value' />
                        ";
                    }
                    echo "
                        <div class='d-flex justify-content-evenly mt-2'>
                            <button type='submit' class='btn btn-primary' name='add_direction' value='$direction_counter, $ingredient_counter'>+</button>
                            <button type='submit' class='btn btn-primary' name='remove_direction' value='$direction_counter, $ingredient_counter'>-</button>
                        </div>
                    ";
                ?>
            </div>
        </form>
        <div class="feedback">
            <?php    
                if (!isset($_SESSION['username'])) {
                    header("Location: home.php");
                }
            ?>
            <?php
                if (is_submitted($ingredient_counter, $direction_counter)) {
                    $public = 0;
                    $favorite = 0;
                    if (isset($_POST['public'])) {
                        $public = 1;
                    }
                    if (isset($_POST['favorite'])) {
                        $favorite = 1;
                    }

                    $ingredients = array();
                    for ($i = 1; $i < $ingredient_counter + 1; $i++) {
                        array_push($ingredients, $_POST["ingredient$i"]);
                    }
                    $ingredients = join(",", $ingredients);

                    $directions = array();
                    for ($i = 1; $i < $direction_counter + 1; $i++) {
                        array_push($directions, $_POST["direction$i"]);
                    }

                    $directions = join(".", $directions);
                    $recipe = new Recipe(get_user_by_id($db, $_SESSION['username']),
                        $_POST['name'],
                        $_POST['pictureURL'],
                        $ingredients,
                        $directions,
                        $public,
                        $favorite
                    );
                    
                    if (recipe_exists($recipe_db, $recipe->get_name()) && isset($_POST['clicked'])) {
                        $response = santize_input($recipe);
                        if ($response === true) {                                $name = $recipe->get_name();
                            $sql = "UPDATE recipes SET pictureURL = (?) WHERE name = '$name'";
                            sql_query($conn, $sql, 's', [$recipe->get_pictureURL()]);

                            $sql = "UPDATE recipes SET ingredients = (?) WHERE name = '$name'";
                            sql_query($conn, $sql, 's', [join(",", $recipe->get_ingredients())]);

                            $sql = "UPDATE recipes SET directions = (?) WHERE name = '$name'";
                            sql_query($conn, $sql, 's', [join(".", $recipe->get_directions())]);

                            header('Location: home.php');
                            echo "Recipe Creation Successful!";
                        } else {
                            echo $response;
                        } 
                    }
                }
            ?>
        </div>
        <button type="submit" name="clicked" value="<?php echo $ingredient_counter . "," . $direction_counter ?>" class="btn btn-primary mt-2">Submit</button>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>