<?php

require_once('../private/process/connect.php');
$connection = db_connect();

$title = "Sign Up | The Chinese Drama Catalogue";
include('includes/header.php');

include('../private/process/functions.php');

$message = (isset($message)) ? $message : "";

include('../private/process/signup-process.php');

?>

<main class="container">
    <section class="row justify-content-center">
        <div class="col-md-8 col-xl-6">
            <h1>Sign Up</h1>
            <p class="lead">Join our Chinese Drama Catalog to share your insights and discover new gems. Sign up now to be part of the excitement!</p>

            <div>
                <?php echo $message; ?>
            </div>

            <!-- Login Form -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="form" enctype="multipart/form-data">
                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control cursor-text" value="<?php if(isset($username)) echo $username; ?>" required>
                </div>

                <!-- First Name -->
                <div class="mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" name="firstname" id="firstname" class="form-control cursor-text" value="<?php if(isset($firstname)) echo $firstname; ?>" required>
                </div>

                <!-- Last Name -->
                <div class="mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" name="lastname" id="lastname" class="form-control cursor-text" value="<?php if(isset($lastname)) echo $lastname; ?>" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control cursor-text" value="<?php if(isset($email)) echo $email; ?>" required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control cursor-text" value="<?php if(isset($password)) echo $password; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="avatar" class="form-label">User Avatar</label>
                    <input type="file" name="avatar" id="avatar" class="form-control" required>
                </div>

                <!-- Submit -->
                <input type="submit" value="Sign Up" name="signup" class="btn btn-warning" id="signup">
            </form>
        </div>
    </section>
</main>





<?php

include('includes/footer.php');

?>