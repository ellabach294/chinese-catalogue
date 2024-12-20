<?php

require_once('../process/connect.php');
$connection = db_connect();

require_once('../process/prepared.php');

$title = "User Setting | The Chinese Drama Catalogue";
include('../includes/header.php');


$username = isset($_POST['username']) ? trim($_POST['username']) : "";
$firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : "";
$lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : "";
$email = isset($_POST['email']) ? trim($_POST['email']) : "";

$message = "";

if (!isset($_SESSION['username'])) {
    header('Location: ../../public/index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $id = '';
}

if (isset($id) && $id !== "") {
    if (is_numeric($id) && $id > 0) {
        $selected_user = get_user_profile_by_id($id);
        if ($selected_user) {
            $existing_username = $selected_user['username'];
            $existing_firstname = $selected_user['firstname'];
            $existing_lastname = $selected_user['lastname'];
            $existing_email = $selected_user['email'];
            $existing_img = $selected_user['profile_img'];
        } else {
            $message .= "<p class=\"alert alert-danger\" role=\"alert\">Sorry, there are no records available that match your selected.</p>";
        }
    }
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($connection, $_POST['lastname']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    $sql = "UPDATE catalogue_admin SET username = ?, firstname = ?, lastname = ?, email = ? WHERE account_id = ?;";
    $statement = $connection->prepare($sql);
    $statement->bind_param("ssssi", $username, $firstname, $lastname, $email, $id);
    if ($statement->execute()) {
        $message .= "<p class=\"alert alert-info\">You have successfully updating your account.</p>";
        //blank out the form data so the user can't resubmit
        $_SESSION['username'] = $username;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['email'] = $email;
    } else {
        $message .= "<p class=\"alert alert-danger\">An error occurred while registering: " . $statement->error . "</p>";
    }

    $time = time();
    $avatar_name = $time . $_FILES['image']['name'];
    $avatar_size = $_FILES['image']['size'];
    $avatar_tmp_name = $_FILES['image']['tmp_name'];
    $avatar_destination_path = __DIR__ . '/../../public/img_upload/profile_img/' . $avatar_name;

    $allowed_files = ['png', 'jpg', 'jpeg'];
    $extension = explode('.', $avatar_name);
    $extension = end($extension);

    if (in_array($extension, $allowed_files)) {
        if ($avatar_size > 1000000) {
            $message .= "<p>Avatar file size is too big, it should be less than 1MB</p>";
        } else {
            $sql = "UPDATE catalogue_admin SET profile_img = ? WHERE account_id = ?;";
            $statement = $connection->prepare($sql);
            $statement->bind_param("si", $avatar_name, $id);
            if ($statement->execute()) {
                move_uploaded_file($avatar_tmp_name, $avatar_destination_path);

                if(file_exists($existing_img)) {
                    unlink(__DIR__ . '/../../public/img_upload/profile_img/'.$existing_img);
                }
                $_SESSION['profile_img'] = $avatar_name;
            }
        }
    } else {
        $message .= "<p class=\"alert alert-danger\">An error occurred while registering: " . $statement->error . "</p>";
    }
}

?>

<main class="container">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data"
            class="shadow w-50 p-3">
            <h2 class="display-4">Edit Profile</h2>

            <!-- display error -->
            <?php if (isset($message)): ?>
                <div class="alert" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label lead">Username</label>
                <input type="text" name="username" id="username" class="form-control cursor-text" value="<?php if ($username != "") {
                    echo $username;
                } else
                    echo $existing_username; ?>">
            </div>

            <!-- First Name -->
            <div class="mb-3">
                <label for="firstname" class="form-label lead">First Name</label>
                <input type="text" name="firstname" id="firstname" class="form-control cursor-text" value="<?php if ($firstname != "") {
                    echo $firstname;
                } else
                    echo $existing_firstname; ?>">
            </div>

            <!-- Last Name -->
            <div class="mb-3">
                <label for="lastname" class="form-label lead">Last Name</label>
                <input type="text" name="lastname" id="lastname" class="form-control cursor-text" value="<?php if ($lastname != "") {
                    echo $lastname;
                } else
                    echo $existing_lastname; ?>">
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label lead">Email</label>
                <input type="text" name="email" id="email" class="form-control cursor-text" value="<?php if ($email != "") {
                    echo $email;
                } else
                    echo $existing_email; ?>">
            </div>

            <!-- Profile Image -->
            <div class="mb-3">
                <label for="image" class="form-label lead">Profile Image</label>
                <img src="../../public/img_upload/profile_img/<?php if (!empty($_SESSION['profile_img'])) {
                    echo $_SESSION['profile_img'];
                } else {
                    echo 'default-img.png';
                } ?>"
                    alt="<?php echo $existing_username ?> profile image" class="rounded-circle m-2" width="70">
                <input type="file" class="form-control" name="image" id="image">
            </div>

            <!-- Hidden ID -->
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

            <!-- submit -->
            <input type="submit" name="submit" id="submit" value="Save" class="btn btn-warning">
            <a href="admin.php" class="btn btn-info">Back to Dashboard</a>
        </form>
    </div>
</main>


<?php

include('../includes/footer.php');

?>