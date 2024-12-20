<?php

require_once('../process/connect.php');
$connection = db_connect();

require_once('../process/prepared.php');

$title = "Login | The Chinese Drama Catalogue";

include('../includes/header.php');
include('../process/login-process.php');

$message = "";

if(isset($_SESSION['username']) && isset($_SESSION['id'])) {
    header('Location: admin.php');
    exit();
}

?>

<main class="container">
    <section class="row justify-content-center">
        <div class="col-md-8 col-xl-6">
            <h1>Login</h1>
            <p class="lead">To access the administrative area for this application, please login down below.</p>

            <?php if($message != NULL) : ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control cursor-text" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control cursor-text" required>
                </div>

                <!-- Submit -->
                <input type="submit" value="Login" name="login" class="btn btn-warning" id="login">
                <small>Don't have account? <a href="../../public/signup.php">Sign Up</a> </small>
            </form>
        </div>
    </section>
</main>


<?php

include('../includes/footer.php');

?>