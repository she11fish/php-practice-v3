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
        <div class='fs-1'>Create Recipe</div>
        <form class="d-flex w-50 justify-content-evenly align-items-start" id="input_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div>
                <div class='fs-4'>Name</div>
                <input type="text" name="name" />
                <div class='fs-4'>Image URL</div>
                <input type="text" name="pictureURL" />
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
            <div class='d-flex flex-column align-start'>
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
                <div>
                    <input class="d-inline" type="checkbox" name="public" value="checked" />
                    <div class="d-inline fs-4">Public</div> 
                </div>
                <div>
                    <input class="d-inline" type="checkbox" name="favorite" value="checked" />
                    <div class="d-inline fs-4" >Favorite</div>
                </div>
            </div>
        </form>
        <form id="input_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
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
                        
                        if (!recipe_exists($recipe_db, $recipe->get_name())) {
                            $response = santize_input($recipe);
                            if ($response === true) {
                                $sql = "INSERT INTO recipes (userid, name, pictureURL, ingredients, directions, public, favorite) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                sql_query($conn, $sql, 'issssii', 
                                    [
                                        $recipe->get_userid(), 
                                        $recipe->get_name(), 
                                        $recipe->get_pictureURL(), 
                                        join(",", $recipe->get_ingredients()), 
                                        join(".", $recipe->get_directions()), 
                                        $recipe->get_public(), 
                                        $recipe->get_favorite()
                                    ]);
                                header('Location: home.php');
                                echo "Recipe Creation Successful!";
                                
                            } else {
                                echo $response;
                            } 
                        } else if($recipe->get_name() != "" && $recipe->get_pictureURL() != "") {
                            echo "Recipe already exists";
                        }
                    }
            ?>
        </div>
        <button type="submit" name="clicked" value="<?php echo $ingredient_counter . "," . $direction_counter ?>" class="btn btn-primary my-3 fs-4">Submit</button>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>