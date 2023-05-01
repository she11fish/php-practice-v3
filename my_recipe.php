<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>

    <title>Recipe Creator</title>
</head>

<body class="bg-light">
    <?php 
        include 'navbar.php';
        include 'recipe_connection.php';
        include 'user_connection.php';
        include 'recipe_tools.php';
        include 'recipe.php';
    ?>
    <div class='container d-flex flex-column align-items-center'>
        <?php
            foreach ($recipe_db as $data) {
                $recipe = new Recipe(
                    $data['userid'], 
                    $data['name'], 
                    $data['pictureURL'],
                    $data['ingredients'],
                    $data['directions'],
                    $data['public'],
                    $data['favorite'],
                ); 
                if (!isset($_SESSION['username'])) {
                    header("Location: home.php");
                }
                $id = get_user_by_id($db, $_SESSION['username']);
                if ($recipe->get_userid() != $id) {
                    continue;
                }
                $name = $recipe->get_name();
                $imageURL = $recipe->get_pictureURL();
                $public = $recipe->get_public();
                $favorite = $recipe->get_favorite();
                echo "<div class='card container my-5 p-3'>  
                <div class='dropdown d-flex justify-content-end'>
                            <button class='btn btn-primary dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                Options
                          </button>
                                <ul class='dropdown-menu dropdown-menu-end'>
                                    <form action='edit_recipe.php' method='post'>
                                    "; 
                                        $i = 1;
                                        foreach ($recipe->get_ingredients() as $ingredient) {
                                            echo "<input type='text' style='display: none;' name='ingredient$i' value='$ingredient' />";
                                            $i++;
                                        }
                                        $d = 1;
                                        foreach ($recipe->get_directions() as $direction) {
                                            echo "<input type='text' style='display: none;' name='direction$d' value='$direction' />";
                                            $d++;
                                        }
                                        echo "<input type='text' style='display: none;' name='clicked' value='$i,$d' />";
                                    echo "
                                        <input type='text' style='display: none;' name='pictureURL' value='$imageURL' />
                                        <li><button type='submit' class='dropdown-item bg-info' name='name' value='$name' href='./edit_recipe.php'>Edit</button></li>
                                    </form>";
                                    if ($public == 1) {
                                        echo "<form action='toggle_public.php' method='post'>
                                                    <li><button type='submit' class='dropdown-item bg-danger' name='public' value='$public,$name'>Private</button></li>
                                            </form>
                                            ";
                                    } else {
                                        echo "<form action='toggle_public.php' method='post'>
                                                    <li><button type='submit' class='dropdown-item bg-success' name='public' value='$public,$name'>Public</button></li>
                                            </form>
                                            ";
                                    }
                                    if ($favorite == 1) {
                                        echo "<form action='toggle_favorite.php' method='post'>
                                        <li><button type='submit' class='dropdown-item bg-warning' name='favorite' value='$favorite,$name'>Favorite</button></li>
                                    </form>";
                                    } else {
                                        echo "<form action='toggle_favorite.php' method='post'>
                                        <li><button type='submit' class='dropdown-item' name='favorite' value='$favorite,$name'>Favorite</button></li>
                                    </form>";
                                    }
                                    
                                    echo "<li><hr class='dropdown-divider m-0'></li>
                                    <form class='target' action='remove_recipe.php' method='post'>
                                        <button type='button' name='name' value='$name' onclick='handleSubmit(". '"'. $name . '"' . ");' class='dropdown-item bg-danger' >Remove</button>
                                    </form>
                                </ul>
                        </div>
                <div class='row'>
                    <div class='col fs-1 d-flex justify-content-center align-items-center'>" . $recipe->get_name() . "</div>
                    ";
                echo "<div class='row'>
                    <img class='col' src=" . $recipe->get_pictureURL() . " alt='Recipe Image' style='width: 25%'/>
                    "; 
                    echo "<div class='col'>
                    <div class='fs-1'>Ingredients</div>
                    <ul class='fs-5'>";
                        foreach ($recipe->get_ingredients() as $ingredient) {
                            echo "<li class='fs-5'>$ingredient</li>";
                        }
                    echo "
                            </ul>
                        </div>
                    </div>";
                echo "<div class='col'>
                        <div class='fs-1'>Directions</div>
                        <ol>"; 
                    foreach ($recipe->get_directions() as $direction) {
                        echo "<li class='fs-5'>$direction</li>";
                    }
                echo "</ol>
                    </div>      
                </div>
            </div>";
            }
        ?>
        <a class='btn btn-primary mb-5'  href='./create_recipe.php' role='button'>Create a New Recipe</a>
        <div class='response'></div>
    </div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    function handleSubmit(name) {
            $.ajax({
            url: "./remove_recipe.php",
            type: "post",
            data: {
                name: name
            },

            success: function (data) {
                $('.response').html(data);
            }
        });
    }
</script>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js' integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM' crossorigin='anonymous'></script>
</html>