<?php

if(isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username && $password) {

        $statement = $connection->prepare('SELECT * FROM catalogue_admin WHERE username = ?;');

        $statement->bind_param('s', $username);
        $statement->execute();

        $result = $statement->get_result();

        if($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if(password_verify($password, $row['hashed_password'])) {

                session_regenerate_id();

                $_SESSION['username'] = $row['username'];

                $_SESSION['id'] = $row['account_id'];

                $_SESSION['profile_img'] = $row['profile_img'];

                $_SESSION['last_login'] = time();

                $_SESSION['login_expires'] = strtotime("+1 day midnight");

                header("Location: admin.php?id=". $row['account_id']);
            } else {
                $message = "<p class=\"text-warning\">Invalid username or password.</p>";
            }
        } else {
            $message = "<p class=\"text-warning\">Invalid username or password.</p>";
        }
    } else {
        $message = "<p class=\"text-warning\">Both fields are required.</p>";
    }
}

?>