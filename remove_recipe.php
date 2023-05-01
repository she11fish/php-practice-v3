<div class='container d-flex flex-column justify-content-center align-items-center' style='height: 100vh;'>
    <!-- Button trigger modal -->
        <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal'>
        Launch demo modal
        </button>

        <!-- Modal -->
        <div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
            <div class='modal-header'>
                <h1 class='modal-title fs-5' id='exampleModalLabel'>Delete Confirmation</h1>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                Are you sure you want delete this recipe?
            </div>
            <div class='modal-footer'>
                <form action='remove_recipe.php' method='post'>
                    <button type='submit' name='response' value='no|<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>' class='btn btn-success' data-bs-dismiss='modal'>No</button>
                    <button type='submit' name='response' value='yes|<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>' class='btn btn-danger'>Yes</button>
                </form>
            </div>
            </div>
        </div>
        </div>
        <?php
                if(isset($_POST['response'])) {
                    $response = explode('|', $_POST['response'])[0];
                    echo ($response);
                    if ($response == 'no') {
                        header('Location: my_recipe.php');
                    } else if ($response == 'yes') {
                        echo 'Deleted Recipe';
                        $name = explode('|', $_POST['response'])[1];
                        $sql = 'DELETE FROM recipes WHERE name = (?)';
                        $types = 's';
                        $placeholders = [$name];
                        sql_query($conn, $sql, $types, $placeholders);
                        header('Location: my_recipe.php');
                    }
                    
                } 
            ?>
</div>
<script>
    var galleryModal = new bootstrap.Modal('#exampleModal', {
  keyboard: false
});


galleryModal.show();
</script>