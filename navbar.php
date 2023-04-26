<nav class="navbar container-fluid fs-1">
    <a href="./home.php">Home</a>
    <?php   
        session_start();  
        if (!isset($_SESSION['username'])) {
          echo '<a href="./register.php">Register</a>
          <a href="./login.php">Login</a>
          ';
        } else {
            echo '<a href="./my_recipe.php">My Recipes</a>
            <a href="./favorite.php">Favorites</a>
            <a href="./create_recipe.php">Create Recipe</a>
            <a href="./edit_recipe.php">Edit Recipe</a>
            <a href="./remove_recipe.php">Remove Recipe</a>
            <a href="./logout.php">Logout</a>';
        }
    ?>
    
</nav>