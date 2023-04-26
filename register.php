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
        include "user_connection.php";
    ?>
    <div class="container" style="height: 100vh;">
        <form class="d-flex flex-column justify-content-center align-items-center" action="register.php" method="post">
            <div>Regsiter</div>
            <div>Username</div>
            <input type="text" name="username" />
            <div>Password</div>
            <input type="password" name="password" />
            <div class="feedback">
            <?php    
                if (isset($_SESSION['username'])) {
                    header("Location: home.php");
                }
            ?>
            <?php
                include "user_connection.php";
                include "user_tools.php";
                if (is_submitted()) {
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    if (!username_exists($db, $username)) {
                        $response = santize_input($username, $password);
                        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                        if ($response === true) {
                            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                            sql_query($conn, $sql, 'ss', [$username, $hashed_password]);
                            $_SESSION['username'] = $username;
                            $_SESSION['password'] = $hashed_password;
                            echo "Registration Successful!";
                            header('Location: home.php');
                        } else {
                            echo $response;
                        } 
                    } else {
                        echo "Username already taken";
                    }
                }
            ?>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>