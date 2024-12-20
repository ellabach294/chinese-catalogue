<?php

require_once('../private/process/connect.php');
$connection = db_connect();

require_once('../private/process/prepared.php');

$title = "Drama Details";
include('includes/header.php');

$title = $_GET['title'] ? $_GET['title'] : 'No available drama.';
if (isset($_GET['drama_id']) && is_numeric($_GET['drama_id']) && $_GET['drama_id'] > 0) {
    $drama_id = $_GET['drama_id'];
} else {
    $message = "<p>Please return to the homepage and select an option from the table.</p>";
    $drama_id = NULL;
}

?>

<main class="container flex-column d-flex align-items-center">
    <div class="col-md-12 col-lg-10 col-xxl-8">
        <?php

        if ($title === "No available drama.") {
            echo "<h2>Oops!</h2>";
            echo "<p>There is no available drama with your input title in our database</p>";
        } else {
            $query = "SELECT * FROM drama_catalogue WHERE title = ?;";
            $statement = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($statement, "s", $title);
            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);


            if (!$result) {
                die("Query failed: " . mysqli_error($connection));
            } else {
                $row = mysqli_fetch_assoc($result);

                if (!$row) {
                    echo "<h2 class=\"text-danger text-center\">Oops!</h2>";
                    echo "<p class=\"text-center\">No available drama title found.</p>";
                } else {
                    echo '<div class="card">';
                    echo '<div class="card-header text-bg-warning d-flex align-items-center justify-content-between">';
                    echo '<h2 class="display-6">' . $title . '</h2>';
                    echo '<div>';
                    echo '<a href="./../private/admin/edit.php" class="btn btn-light me-4">Edit</a>';
                    echo '<a href="./../private/admin/delete.php" class="btn btn-danger">Delete</a>';
                    echo '</div>';
                    echo '</div>';
                    ?>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <!-- Image -->
                            <div class="col d-flex flex-column align-items-center">
                                <img src="img_upload/drama_img/full/<?php echo $row['image_filename']; ?>"
                                    alt="<?php echo $title; ?> drama poster" class="img-fluid rounded">

                                <p>Source: <a href="<?php echo $row['image_url']; ?>">My Drama List</a></p>
                            </div>
                            <!-- Info -->
                            <div class="col-lg-6">
                                <!-- Rating -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="fw-bold">Rating: </p>
                                    <div>
                                        <?php
                                        echo '<div class="d-flex d-row">';
                                        echo '<p class="me-2 fw-medium fs-5">' . $row['average_rating']. '</p>';
                                        for ($i = 1; $i <= 5; $i++) {
                                            if (round($row['average_rating'] - 0.25) >= $i) {
                                                echo "<span class='text-warning fs-5'>&#9733;</span>";
                                            } elseif (round($row['average_rating'] + 0.25) >= $i) {
                                                echo "<span class='text-warning fs-5'>&#9734;</span>";
                                            } else {
                                                echo "<span class='text-warning fs-5'>&#9734;</span>";
                                                ;
                                            }
                                        }
                                        echo '</div>';
                                        ?>
                                    </div>
                                </div>

                                <!-- Release Year -->
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold">Release Year: </p>
                                    <p>
                                        <?php echo $row['release_year']; ?>
                                    </p>
                                </div>

                                <!-- Episodes -->
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold">Episodes: </p>
                                    <p>
                                        <?php echo $row['episodes']; ?>
                                    </p>
                                </div>

                                <!-- Category -->
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold">Category: </p>
                                    <p>
                                        <?php echo ucfirst($row['category']); ?>
                                    </p>
                                </div>

                                <!-- Original Network -->
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold">Original Network: </p>
                                    <p>
                                        <?php echo $row['original_network']; ?>
                                    </p>
                                </div>

                                <!-- Director -->
                                <div class="d-flex flex-wrap justify-content-between">
                                    <p class="fw-bold">Director: </p>
                                    <p>
                                        <?php echo $row['director']; ?>
                                    </p>
                                </div>

                                <!-- Screenwriter -->
                                <div class="d-flex flex-wrap justify-content-between">
                                    <p class="fw-bold">Screenwriter: </p>
                                    <p>
                                        <?php echo $row['screenwriter']; ?>
                                    </p>
                                </div>

                                <!-- Cast -->
                                <div class="d-flex flex-wrap justify-content-between">
                                    <p class="fw-bold">Main Cast: </p>
                                    <p>
                                        <?php echo $row['cast']; ?>
                                    </p>
                                </div>

                                <!-- Description -->
                                <div>
                                    <p class="fw-bold">Description:</p>
                                    <p>
                                        <?php echo $row['description'] ?>
                                    </p>
                                </div>

                                <div>
                                    <p class="fw-bold">FunFacts:</p>
                                    <p>
                                        <?php echo $row['funfacts'] ?>
                                    </p>
                                </div>
                                
                                <!-- Return Homepage -->
                                <a href="index.php" class="btn btn-warning mt-3">Back to the Homepage</a>

                            </div>
                        </div>
                    </div>
                    <?php
                    echo '</div>';
                }
            }
        }

        ?>

    </div>

    <div class="col-md-12 col-lg-10 col-xxl-8 mt-5">
        <div class="d-flex justify-content-between">
            <h3>Rating & Review</h3>
            <a href="add-review.php?title=<?php echo $title; ?>&drama_id=<?php echo $drama_id ?>"
                class="btn btn-warning fs-6">Add Review</a>
        </div>
    </div>

    <div class="col-md-12 col-lg-10 col-xxl-8 mt-5">
        <?php
        $sql = "SELECT * FROM user_rating INNER JOIN catalogue_admin ON user_rating.account_id = catalogue_admin.account_id WHERE drama_id = $drama_id;";
        $result = $connection->query($sql);

        if ($connection->error) {
            echo $connection->error;
        } elseif ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                extract($row);
                echo "
                        <div class=\"border shadow p-3 mb-3\">
                            <div class=\"d-flex\">
                            <img src=\"img_upload/profile_img/$profile_img\" alt=\"$username profile image\" class=\"rounded-circle me-3\" width=\"40\" height=\"40\">
                            <p class=\"fs-4 fw-bold\">$username</p>
                            </div>
                            <div class=\"d-flex justify-content-between\">
                            <p class=\"fw-bold\">$title</p>
                            <div class=\"stars fs-4\">";
                echo "<span>";
                for ($i = 1; $i <= 5; $i++) {
                    if (round($rating - 0.25) >= $i) {
                        echo "<span class='text-warning'>&#9733;</span>";
                    } elseif (round($rating + 0.25) >= $i) {
                        echo "<span class='text-warning'>&#9734;</span>";
                    } else {
                        echo "<span class='text-warning'>&#9734;</span>";
                        ;
                    }
                }
                echo '</span>';
                echo "</div>
                    </div>
                    <p>$description</p>
                    </div>";
            }
        }
        ?>
    </div>
</main>

<?php

include('includes/footer.php');

?>