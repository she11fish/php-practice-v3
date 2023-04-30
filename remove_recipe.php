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
        include "recipe_tools.php";
        include "recipe.php";
    ?>
    <div class="container d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">
        <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Launch demo modal
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want delete this recipe?
                </div>
                <div class="modal-footer">
                    <form action="remove_recipe.php" method="post">
                        <button type="submit" name='response' value="no|<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>" class="btn btn-success" data-bs-dismiss="modal">No</button>
                        <button type="submit" name='response' value="yes|<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>" class="btn btn-danger">Yes</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
            <?php
                    if(isset($_POST['response'])) {
                        $response = explode("|", $_POST['response'])[0];
                        echo ($response);
                        if ($response == "no") {
                            header("Location: my_recipe.php");
                        } else if ($response == "yes") {
                            echo "Deleted Recipe";
                            $name = explode("|", $_POST['response'])[1];
                            $sql = "DELETE FROM recipes WHERE name = (?)";
                            $types = "s";
                            $placeholders = [$name];
                            sql_query($conn, $sql, $types, $placeholders);
                            header("Location: my_recipe.php");
                        }
                        
                    } 
                ?>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>