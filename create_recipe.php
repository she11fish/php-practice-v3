<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Document</title>
</head>

<body>
    <?php 
        include "navbar.php";
        include "recipe_connection.php";
        include "user_connection.php";
        include "recipe_tools.php";
        include "recipe.php";
    ?>
    <div class="container" style="height: 100vh;">
        <form class="d-flex flex-column justify-content-center align-items-center" action="create_recipe.php" method="post">
            <div>Create Recipe</div>
            <div>Name</div>
            <input type="text" name="name" />
            <div>Image URL</div>
            <input type="text" name="pictureURL" />
            <?php
                $ingredient_counter = 5;
                if (isset($_POST['clicked'])) {
                    echo "HERE";
                    $ingredient_counter = $_POST['clicked'];
                    $ingredient_counter++;
                }
                for ($i = 1; $i < $ingredient_counter + 1; $i++) {
                    echo "
                        <div>Ingredient$i</div>
                        <input type='text' name='ingredient$i' />
                    ";
                }
                // echo "
                //     <form action='create_recipe.php' method='post'>
                //         <button type='submit' class='btn btn-primary' name='clicked' value='$ingredient_counter'>+</button>
                //     </form>
                // ";
            ?>
            <div>Directions</div>
            <input type="text" name="directions" />
            <div>
                <input class="d-inline" type="checkbox" name="public" value="checked" />
                <div class="d-inline">Public</div> 
            </div>
            <div>
                <input class="d-inline" type="checkbox" name="favorite" value="checked" />
                <div class="d-inline" >Favorite</div>
            </div>
            
            <div class="feedback">
            <?php    
                if (!isset($_SESSION['username'])) {
                    header("Location: home.php");
                }
            ?>
            <?php
                if (is_submitted($ingredient_counter)) {
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
                    $recipe = new Recipe(get_user_by_id($db, $_SESSION['username']),
                        $_POST['name'],
                        $_POST['pictureURL'],
                        $ingredients,
                        $_POST['directions'],
                        $public,
                        $favorite
                    );
                    
                    if (!recipe_exists($recipe_db, $recipe->get_name())) {
                        $response = santize_input($recipe);
                        if ($response === true) {
                            $sql = "INSERT INTO recipes (userid, name, pictureURL, ingredients, directions, public, favorite) VALUES (?, ?)";
                            sql_query($conn, $sql, 'issssii', 
                                [
                                    $recipe->get_userid(), 
                                    $recipe->get_name(), 
                                    $recipe->get_pictureURL(), 
                                    $recipe->get_ingredients(), 
                                    $recipe->get_directions(), 
                                    $recipe->get_public(), 
                                    $recipe->get_favorite()
                                ]);
                            echo "Recipe Creation Successful!";
                            header('Location: home.php');
                        } else {
                            echo $response;
                        } 
                    } else {
                        echo "Recipe already exists";
                    }
                }
            ?>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>