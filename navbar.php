<nav class="navbar container-fluid fs-1 bg-dark">
    <a class="text-white" href="./home.php">Home</a>
    <?php   
        session_start();  
        if (!isset($_SESSION['username'])) {
          echo '<a class="text-white" href="./register.php">Register</a>
          <a  class="text-white"href="./login.php">Login</a>
          ';
        } else {
            echo '<a class="text-white" href="./my_recipe.php">My Recipes</a>
            <a class="text-white" href="./favorite.php">Favorites</a>
            <a class="text-white" href="./logout.php">Logout</a>';
        }
    ?>
    
</nav>