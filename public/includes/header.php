<?php

session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $id = $_SESSION['id'];
    $sql = "SELECT profile_img FROM catalogue_admin WHERE account_id = $id;";
    $result = $connection->query($sql);

    if ($connection->error) {
        echo $connection->error;
    } elseif ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            extract($row);
            $profile_img = 'img_upload/profile_img/' . $row['profile_img'];
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo $title; ?>
    </title>
    <!-- BS Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- BS Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- BS CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body class="d-flex flex-column justify-content-between min-vh-100">
    <header class="text-center">
        <nav class="py-2 bg-dark border-bottom">
            <div class="container d-flex flex-wrap">
                <ul class="nav me-auto">
                    <li class="nav-item"><a href="index.php" class="nav-link link-light link-body-emphasis px-2">Browse
                            by: </a></li>
                    <li class="nav-item"><a href="filter-by-category.php"
                            class="nav-link link-light link-body-emphasis px-2">Category&emsp;|</a></li>
                    <li class="nav-item"><a href="filter-by-network.php"
                            class="nav-link link-light link-body-emphasis px-2">Network&emsp;|</a></li>
                    <li class="nav-item"><a href="filter-by-year.php"
                            class="nav-link link-light link-body-emphasis px-2">Release Year</a></li>
                </ul>

                <div class="d-flex align-items-center">
                    <a href="search.php" class="nav-link link-light link-body-emphasis px-2">Advanced Search</a>

                    <?php if (isset($_SESSION['username'])): ?>
                        <div class="flex-shrink-0 dropdown">
                            <a href="#" class="d-block text-warning text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo $_SESSION['username']; ?>
                            </a>

                            <ul class="dropdown-menu text-small shadow">
                                <li><a href="../private/admin/admin.php" class="dropdown-item">Dashboard</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a href="../private/admin/logout.php" class="text-warning dropdown-item">Log Out</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="../private/admin/login.php" class="nav-link link-light link-body-emphasis px-2">Login</a>
                        <a href="./signup.php" class="nav-link link-light link-body-emphasis px-2">Sign Up</a>
                    <?php endif; ?>
                </div>
        </nav>

        <section class="py-3 mb-4 border-bottom">
            <div class="container d-flex flex-wrap justify-content-center">
                <a href="index.php"
                    class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto link-body-emphasis text-decoration-none">
                    <svg class="bi me-2" width="40" height="32">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                    <h1 class="fs-4 fw-light text-warning"><i class="bi bi-film"></i> The Chinese Drama Catalogue</h1>
                </a>
            </div>
        </section>
    </header>