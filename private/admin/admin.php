<?php

require_once('../process/connect.php');
$connection = db_connect();

$title = "Dashboard Area | The Chinese Drama Catalogue";
include('../includes/header.php');

if (!isset($_SESSION['username'])) {
    header('Location: ../../public/index.php');
    exit();
}

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    $username = $_SESSION['username'];
    $id = $_SESSION['id'];
    $profile_img = $_SESSION['profile_img'];
}



?>

<main class="container">
    <section class="text-center">
        <img src="../../public/img_upload/profile_img/<?php if(!empty($profile_img)) {
            echo $profile_img;
        } else {
            echo 'default-img.png';
        } ?>" alt="<?php echo $username ?> image profile" class="rounded-circle" width="100" height="100">
        <h2 class="fw-light mt-3">It's good to see you,
            <?php echo $username ?>!
        </h2>
        <p class="lead">If this were a fully-fledged application, we'd have all of the more dangerous CRUD operations
            hidden when you are admin. In this dashboard, you will be able to add a new drama, edit or delete the
            existing drama in our database.</p>
    </section>

    <div class="text-center">
        <a href="user.php?id=<?php echo $id; ?>" class="btn btn-info me-2">User Setting</a>
        <a href="add.php" class="btn btn-success">Add</a>
        <a href="edit.php" class="btn btn-warning mx-2">Edit</a>
        <a href="delete.php" class="btn btn-danger">Delete</a>
    </div>
</main>

<?php

include('../../public/includes/footer.php');

?>