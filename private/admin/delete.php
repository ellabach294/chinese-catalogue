<?php

require_once('../process/connect.php');
$connection = db_connect();

require_once('../process/prepared.php');

$title = "Delete Drama | The Chinese Drama Catalogue";
include('../includes/header.php');

$per_page = 8;
$total_count = count_records();
$total_pages = ceil($total_count/$per_page);
$current_page = (int) ($_GET['page'] ?? 1);

if($current_page < 1 || $current_page > $total_pages || !is_int($current_page)) {
    $current_page = 1;
};

$offset = $per_page * ($current_page - 1);

?>

<main class="container">
    <section class="row mb-5">
        <h2 class="text-center mt-5 display-5">Delete Drama</h2>
        <p class="lead text-muted text-center">To delete a drama from our database, press the 'Delete' button on the bottom of each drama card.</p>

        <?php if(!isset($_SESSION['username'])) : ?>
            <div class="alert alert-warning mb-5">
                <p class="text-muted text-center">Please login to your account to be able to use the delete feature.</p>
                <p class="text-center"><a href="login.php">Click here to login into your account.</a></p>
            </div>
        <?php endif; ?>
    </section>

    <section class="row">
        <?php
            $result = pagination_records($per_page, $offset);

            if($connection->error) {
                echo $connection->error;
            } elseif ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    extract($row);
                    echo "
                    <div class=\"col-md-6 mb-3\">
                        <div class=\"row border rounded m-0 flex-md-row mb-4 shadow-sm h-md-250 position-relative\">
                            <div class=\"col p-4 d-flex flex-column position-static\">
                                <h3 class=\"mb-0\">". $title . "</h3>
                                <div class=\"d-flex justify-content-between\">
                                    <div class=\"my-1 text-body-secondary\">" . $release_year . "</div>
                                    <div class=\"stars\">";
                                    echo "<span>";
                                    for ($i = 1; $i <= 5; $i++) {
                                        if(round($average_rating - 0.25) >= $i) {
                                            echo "<span class='text-warning'>&#9733;</span>";
                                        } elseif (round($average_rating + 0.25) >= $i) {
                                            echo "<span class='text-warning'>&#9734;</span>";
                                        } else {
                                            echo "<span class='text-warning'>&#9734;</span>";;
                                        }
                                    }
                                    echo '</span>';
                                    echo "
                                    </div>
                                </div>
                                <p class=\"card-text mb-3\">". $original_network . "</p>
                                <p class=\"card-text mb-auto\">". $cast . "</p>

                                <div class=\"btn-group btn-group\">
                                    <button class=\"btn btn-secondary\"><a href=\"single-view.php?title=" . urlencode($title) . "\" class=\"link-light\">View Details</a></button>";
                                    if(isset($_SESSION['username'])) {
                                        echo "<a href=\"delete-confirmation.php?id=". urlencode($drama_id) . "&title=" . urlencode($title) . " \"
                                        class=\"btn btn-danger\">Delete</a>";
                                        }
                                    echo "</div>";
                                echo '</div>';
                            echo "<div class=\"col-auto d-lg-block pe-0\">
                                <img src=\"../../public/img_upload/drama_img/thumbs/$image_filename\" width=\"220\" height=\"300\" alt=\"$title drama poster thumbnail\" class=\"card-img-top rounded-e\">
                            </div>
                        </div>
                    </div>";
            }
        }
        ?>

<nav aria_label="Page Number">
            <ul class="pagination justify-content-center">
                <?php if ($current_page > 1): ?>
                    <li class="page-item">
                        <a href="delete.php?page=<?php echo $current_page - 1; ?>" class="page-link">Previous</a>
                    </li>
                <?php endif;

                $gap = false;
                $window = 1;

                for ($i = 1; $i <= $total_pages; $i++) {
                    if ($i > 1 + $window && $i < $total_pages - $window && abs($i - $current_page) > $window) {
                        if (!$gap): ?>
                            <li class="page-item">
                                <span class="page-link">
                                    ...
                                </span>
                            </li>
                        <?php endif;

                        $gap = TRUE;
                        continue;
                    }

                    $gap = FALSE;

                    if ($current_page == $i): ?>
                        <li class="page-item active">
                            <a href="delete.php?page=<?php echo $i; ?>" class="page-link">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a href="delete.php?page=<?php echo $i; ?>" class="page-link">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endif;
                }
                ?>

                <?php if ($current_page < $total_pages): ?>
                    <li class="page-item">
                        <a href="delete.php?page=<?php echo $current_page ?>" class="page-link">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </section>
</main>

<?php 
include('../../public/includes/footer.php');
?>