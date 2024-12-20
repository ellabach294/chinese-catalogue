<?php

require_once('../private/process/connect.php');
$connection = db_connect();

require_once('../private/process/prepared.php');

$title = "Release Year Filter | The Chinese Drama Catalogue";
include('includes/header.php');

$year_filters = [
    "release_year" => [
        "2016-2018" => "2016 - 2018",
        "2019-2021" => "2019 - 2021",
        "2022-2024" => "2022 - 2024",
    ]
];

$active_filters = [];
foreach ($_GET as $filter => $values) {
    if (!is_array($values)) {
        $values = [$values];
    }
    $active_filters[$filter] = array_map("htmlspecialchars", $values);
}

?>

<main class="container">
    <section class="row justify-content-center">
        <div class=" text-center">
            <h2 class="display-5 text-info fw-light">Browse by Drama Release Year</h2>
            <p class="mb-5">Select any combination of the buttons below to filter the drama by release year.</p>

            <?php
            foreach ($year_filters as $filter => $option) {
                $release_year = ucwords($filter);
                echo "<div class=\"mb-3\" role=\"group\" aria-label=\"" . htmlspecialchars($release_year) . " Filter\">";

                foreach ($option as $value => $label) {
                    $is_active = in_array($value, $active_filters[$filter] ?? []);
                    $updated_filters = $active_filters;
                    if ($is_active) {
                        $updated_filters[$filter] = array_diff($updated_filters[$filter], [$value]);

                        if (empty($updated_filters[$filter])) {
                            unset($updated_filters[$filter]);
                        }
                    } else {
                        $updated_filters[$filter][] = $value;
                    }
                    $url = $_SERVER['PHP_SELF'] . "?" . http_build_query($updated_filters);
                    echo '<a href="' . htmlspecialchars($url) . '" class="btn ' . ($is_active ? "btn-info" : "btn-outline-info") . ' me-3 mb-3">' . htmlspecialchars($label) . '</a>';

                }

                echo '</div>';
            }
            echo '</div>';

            if (!empty($active_filters)): ?>
            </div>
            <hr>
            <div class="row">
                <?php include("includes/filter-year-results.php"); ?>
            </div>
        <?php endif; ?>

        <a href="index.php" class="btn btn-info w-25">Back to the Homepage</a>
    </section>
</main>

<?php
include('includes/footer.php');
?>