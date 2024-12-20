<?php

require_once('../private/process/connect.php');
$connection = db_connect();

require_once('../private/process/prepared.php');

$title = "The Chinese Drama Catalogue";
include('includes/header.php');

$per_page = 6;
$total_count = count_records();
$total_pages = ceil($total_count/$per_page);
$current_page = (int) ($_GET['page'] ?? 1);

if($current_page < 1 || $current_page > $total_pages || !is_int($current_page)) {
    $current_page = 1;
};

$offset = $per_page * ($current_page - 1);
?>

<main class="container">
    <section class="row justify-content-between my-5">

        <!-- Introduction -->
        <div class="col-md-10 col-lg-8 col-xxl-6 mb-4">
            <h2 class="display-4">Welcome to <span class="d-block text-warning">The Chinese Drama Catalogue</span></h2>
            <p>Discover the collection of Chinese Drama with this catalogue. We create the user-friendly platform for
                you to search, sort, rating, review and finding the new drama for your watch-list. Each drama is display
                in English title name, with the information about the release year, original network, director,
                screenwriter, main casts and short description for you. Once you watched the drama, you can log in to
                our database, rating and leave the review for other people to know about your opinion. Join us on this
                cinematic journey with The Chinese Drama Catalogue.</p>
        </div>

        <div
            class="col col-lg-4 col-xxl-3 m-4 m-md-0 mb-md-4 border border-warning rounded p-3 d-flex flex-column justify-content-center align-items-center">
            <h2 class="fw-bold mb-3 fs-5">Drama Fun Facts</h2>
            <?php
            $query = "SELECT * FROM drama_catalogue ORDER BY RAND() LIMIT 1;";
            $random_result = $connection->query($query);

            if ($connection->error) {
                echo $connection->error;
            } elseif ($random_result->num_rows > 0) {
                while ($row = $random_result->fetch_assoc()) {
                    extract($row);
                    echo "<div class=\"text-center\">
                                    <h4 class=\"lead text-info fs-4\">$title <span class=\"fs-5\">($release_year)</span></h4>
                                    <div class=\"stars fs-4\">";
                    echo "<span>";
                    for ($i = 1; $i <= 5; $i++) {
                        if (round($average_rating - 0.25) >= $i) {
                            echo "<span class='text-warning'>&#9733;</span>";
                        } elseif (round(($average_rating) + 0.25) >= $i) {
                            echo "<span class='text-warning'>&#9734;</span>";
                        } else {
                            echo "<span class='text-warning'>&#9734;</span>";
                            ;
                        }
                    }
                    echo '</span>';
                    echo "
                                    </div>
                                    <p>$funfacts</p>
                                </div>";
                }
            }
            ?>
        </div>
    </section>

    <section class="row">
        <h3 class="display-7 text-center mb-3">Top Picks for You</h3>

        <?php
        $sql = "SELECT * FROM drama_catalogue WHERE average_rating > 4.5 ORDER BY RAND() LIMIT 2;";
        $result = $connection->query($sql);

        if ($connection->error) {
            echo $connection->error;
        } elseif ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                extract($row);
                echo "
                    <div class=\"col-lg-6 mb-4\">
                        <div class=\"row border rounded m-0 flex-md-row mb-4 shadow-sm h-md-250 position-relative\">
                            <div class=\"col p-4 d-flex flex-column position-static\">
                                <h3 class=\"mb-0\">" . $title . "</h3>
                                <div class=\"d-flex justify-content-between\">
                                    <div class=\"my-1 text-body-secondary\">" . $release_year . "</div>
                                    <div class=\"stars fs-4\">";
                echo "<span>";
                for ($i = 1; $i <= 5; $i++) {
                    if (round($average_rating - 0.25) >= $i) {
                        echo "<span class='text-warning'>&#9733;</span>";
                    } elseif (round($average_rating + 0.25) >= $i) {
                        echo "<span class='text-warning'>&#9734;</span>";
                    } else {
                        echo "<span class='text-warning'>&#9734;</span>";
                        ;
                    }
                }
                echo '</span>';
                echo "
                                    </div>
                                </div>
                                <p class=\"card-text mb-3\">" . $original_network . "</p>
                                <p class=\"card-text mb-auto\">" . $cast . "</p>
                                <a href=\"single-view.php?title=" . urlencode($title) . "&drama_id=" . urlencode($drama_id) . "\" class=\"py-2 btn btn-dark\">View Details</a>
                            </div>
                            <div class=\"col-auto d-lg-block pe-0\">
                                <img src=\"img_upload/drama_img/thumbs/$image_filename\" width=\"220\" height=\"300\" alt=\"$title drama poster thumbnail\" class=\"rounded\">
                            </div>
                        </div>
                    </div>";
            }
        }
        ?>
    </section>

    <section class="row">
        <h3 class="display-7 text-center mb-3">Newest Drama</h3>

        <?php
        $sql = "SELECT * FROM drama_catalogue WHERE release_year LIKE 2023 ORDER BY RAND() LIMIT 4";
        $result = $connection->query($sql);

        if ($connection->error) {
            echo $connection->error;
        } elseif ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                extract($row);
                echo "
                    <div class=\"col-lg-6 mb-4\">
                        <div class=\"row border rounded m-0 flex-md-row mb-4 shadow-sm h-md-250 position-relative\">
                            <div class=\"col p-4 d-flex flex-column position-static\">
                                <h3 class=\"mb-0\">" . $title . "</h3>
                                <div class=\"d-flex justify-content-between\">
                                    <div class=\"my-1 text-body-secondary\">" . $release_year . "</div>
                                    <div class=\"stars fs-4\">";
                echo "<span>";
                for ($i = 1; $i <= 5; $i++) {
                    if (round($average_rating - 0.25) >= $i) {
                        echo "<span class='text-warning'>&#9733;</span>";
                    } elseif (round($average_rating + 0.25) >= $i) {
                        echo "<span class='text-warning'>&#9734;</span>";
                    } else {
                        echo "<span class='text-warning'>&#9734;</span>";
                        ;
                    }
                }
                echo '</span>';
                echo "
                                    </div>
                                </div>
                                <p class=\"card-text mb-3\">" . $original_network . "</p>
                                <p class=\"card-text mb-auto\">" . $cast . "</p>
                                <a href=\"single-view.php?title=" . urlencode($title) . "&drama_id=" . urlencode($drama_id) . "\" class=\"py-2 btn btn-dark\">View Details</a>
                            </div>
                            <div class=\"col-auto d-lg-block pe-0\">
                                <img src=\"img_upload/drama_img/thumbs/$image_filename\" width=\"220\" height=\"300\" alt=\"$title drama poster thumbnail\" class=\"rounded\">
                            </div>
                        </div>
                    </div>";
            }
        }
        ?>
    </section>

    <section class="row g-2">
        <h3 class="display-7 text-center mb-3">All Dramas</h3>

        <?php

        $result = pagination_records($per_page, $offset);

        if($connection->error) {
            echo $connection->error;
        } elseif ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                extract($row);
                echo "
                    <div class=\"col-lg-6 mb-4\">
                        <div class=\"row border rounded m-0 flex-md-row mb-4 shadow-sm h-md-250 position-relative\">
                            <div class=\"col p-4 d-flex flex-column position-static\">
                                <h3 class=\"mb-0\">" . $title . "</h3>
                                <div class=\"d-flex justify-content-between\">
                                    <div class=\"my-1 text-body-secondary\">" . $release_year . "</div>
                                    <div class=\"stars fs-4\">";
                echo "<span>";
                for ($i = 1; $i <= 5; $i++) {
                    if (round($average_rating - 0.25) >= $i) {
                        echo "<span class='text-warning'>&#9733;</span>";
                    } elseif (round($average_rating + 0.25) >= $i) {
                        echo "<span class='text-warning'>&#9734;</span>";
                    } else {
                        echo "<span class='text-warning'>&#9734;</span>";
                        ;
                    }
                }
                echo '</span>';
                echo "</div>
                        </div>
                            <p class=\"card-text mb-3\">" . $original_network . "</p>
                            <p class=\"card-text mb-auto\">" . $cast . "</p>
                            <a href=\"single-view.php?title=" . urlencode($title) . "&drama_id=" . urlencode($drama_id) . "\" class=\"py-2 btn btn-dark\">View Details</a>
                        </div>
                            <div class=\"col-auto d-lg-block pe-0\">
                                <img src=\"img_upload/drama_img/thumbs/$image_filename\" width=\"220\" height=\"300\" alt=\"$title drama poster thumbnail\" class=\"rounded\">
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
                        <a href="index.php?page=<?php echo $current_page - 1; ?>" class="page-link">Previous</a>
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
                            <a href="index.php?page=<?php echo $i; ?>" class="page-link">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a href="index.php?page=<?php echo $i; ?>" class="page-link">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endif;
                }
                ?>

                <?php if ($current_page < $total_pages): ?>
                    <li class="page-item">
                        <a href="index.php?page=<?php echo $current_page ?>" class="page-link">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

    </section>
</main>


<?php

include('includes/footer.php');

?>