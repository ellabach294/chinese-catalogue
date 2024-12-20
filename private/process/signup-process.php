<?php

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($connection, trim($_POST['username']));
    $firstname = mysqli_real_escape_string($connection, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($connection, trim($_POST['lastname']));
    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
    $password = mysqli_real_escape_string($connection, trim($_POST['password']));
    $avatar = $_FILES['avatar'];

    $message = "";

    $pass_go = TRUE;

    //validate input field
    if(!$username) {
        $message .= "<p>Please enter your username</p>";
        $pass_go = FALSE;
    } elseif(!$firstname) {
        $message .= "<p>Please enter your First Name<p>";
        $pass_go = FALSE;
    } elseif(!$lastname) {
        $message .= "<p>Please enter your Last Name</p>";
        $pass_go = FALSE;
    } elseif(!$password) {
        $message .= "<p>Please enter your Password</p>";
        $pass_go = FALSE;
    } elseif (strlen($password) < 8) {
        $message .= "<p>Password should be 8+ characters</p>";
        $pass_go = FALSE;
    } elseif(!$avatar['name']) {
        $message .= "<p>Please add your avatar.</p>";
        $pass_go = FALSE;
    } else {
        //check if the username or email already exist in the database
        $user_check_query = "SELECT * FROM catalogue_admin WHERE (username='$username' OR email='$email');";
        $user_check_result = mysqli_query($connection, $user_check_query);
        if(mysqli_num_rows($user_check_result) > 0) {
            $row = mysqli_fetch_assoc($user_check_result);

            if($email == isset($row['email'])) {
                $message .= "<p>The provided email address is already associated with a registered account.</p>";
                $pass_go = FALSE;
            } elseif ($username == isset($row['username'])) {
                $message .= "<p>This username is already taken.</p>";
                $pass_go = FALSE;
            }
        } else {
            //work on avatar upload
            //rename avatar
            $time = time();
            $avatar_name = $time . $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination_path = __DIR__ . '/../../public/img_upload/profile_img/' . $avatar_name;

            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $avatar_name);
            $extension = end($extension);
            if(in_array($extension, $allowed_files)) {
                // make sure image is not too large (1mb+)
                if($avatar['size'] < 1000000) {
                    //upload avatar
                    move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                } else {
                    $message .= "<p>Avatar file size is too big, it should be less than 1MB</p>";
                    $pass_go = FALSE;
                }
            } else {
                $message .= "<p>File should be png, jpg or jpeg format.</p>";
                $pass_go = FALSE;
            }
        }
    }

    if($pass_go == TRUE) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        //Created prepared statement the SQL statement with placeholder
        $sql = "INSERT INTO catalogue_admin (username, hashed_password, first_name, last_name, email, profile_img) VALUES (?, ?, ?, ?, ?, ?);";

        $statement = $connection->prepare($sql);

        //bind the variables to the statement
        $statement->bind_param("ssssss", $username, $hashed, $firstname, $lastname, $email, $avatar_name);

        //execute the statement
        if($statement->execute()) {
            $message .= "<p class=\"alert alert-info\">You have successfully registered your account!</p>";
            //blank out the form data so the user can't resubmit
            $username = $firstname = $lastname = $email = $password = $hash = "";
        } else {
            $message .= "<p class=\"alert alert-danger\">An error occurred while registering: " . $statement->error ."</p>";
        }
    }

}

?>