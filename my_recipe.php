<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC' crossorigin='anonymous'>

    <title>Document</title>
</head>

<body>
    <?php 
        include 'navbar.php';
        include 'recipe_connection.php';
        include 'user_connection.php';
        include 'recipe_tools.php';
        include 'recipe.php';
    ?>
    <div class='container' style='height: 100vh;'>
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
                echo "<div class='card container my-5'>  
                <div class='btn-group d-flex justify-content-end'>
                            <div class='row'>
                                <button type='button' class='col btn btn-info'>Options</button>
                                <button type='button' class='col btn btn-info dropdown-toggle dropdown-toggle-split' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <span class='visually-hidden'>Toggle Dropdown</span>
                                </button>
                                <ul class='dropdown-menu'>
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
                                        <li><button type='submit' class='dropdown-item' name='name' value='$name' href='./edit_recipe.php'>Edit</button></li>
                                    </form>
                                    <form action='toggle_public.php' method='post'>
                                        <li><button type='submit' class='dropdown-item' name='public' value='$public,$name'>Toggle Public</button></li>
                                    </form>
                                    <form action='toggle_favorite.php' method='post'>
                                        <li><button type='submit' class='dropdown-item' name='favorite' value='$favorite,$name'>Toggle Favorite</button></li>
                                    </form>
                                    <li><hr class='dropdown-divider'></li>
                                    <form action='remove_recipe.php' method='post'>
                                        <li><button type='submit' class='dropdown-item' name='name' value='$name' >Remove</button></li>
                                    </form>
                                </ul>
                            </div>
                            
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
    </div>
</body>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js' integrity='sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM' crossorigin='anonymous'></script>
</html>