<?php

require_once('../process/connect.php');
$connection = db_connect();

require_once('../process/prepared.php');

$title = "Delete A Drama | The Chinese Drama Catalogue";
include('../includes/header.php');

$drama_title = isset($_GET['title']) ? $_GET['title'] : "";
$message = "";

if(isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    $drama_id = $_GET['id'];
} else {
    $message = "<p>Please return to the 'delete' page and select an option from the table.</p>";
    $drama_id = NULL;
}

if(isset($_POST['confirm'])) {
    $hidden_id = $_POST['hidden_id'];
    delete_drama($hidden_id);
    $message = "<p>The selected drama was deleted from the database.</p>";
}

?>

<main class="container">
    <section>
        <h2 class="fw-light text-center">Deletion Confirmation</h2>

        <?php if($message) : ?>
            <div class="alert alert-danger text-center" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; 
        if($drama_id != NULL) : ?>

            <p class="text-danger lead mb-5 text-center">Are you sure that you want to delete <span class="fw-bold"><?php if(isset($_GET['title'])) echo $_GET['title']; ?></span>?</p>
            
            <form action="<?php echo ($_SERVER['PHP_SELF']); ?>" method="POST">

                <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $drama_id; ?>">

                <input type="submit" class="btn btn-danger d-block mx-auto" name="confirm" id="confirm" value="Yes, I am sure!.">

            </form>
        
        <?php endif; ?>
    </section>
</main>


<?php
include('../../public/includes/footer.php');
?>