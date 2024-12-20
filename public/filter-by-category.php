<?php

require_once('../private/process/connect.php');
$connection = db_connect();

$title = "Category Filter | The Chinese Drama Catalogue";
include('includes/header.php');

$category_filters = [
    "category" => [
        'action' => 'Action',
        'adventure' => 'Adventure',
        'business' => 'Business',
        'comedy' => 'Comedy',
        'drama' => 'Drama',
        'family' => 'Family',
        'fantasy' => 'Fantasy',
        'historical' => 'Historical',
        'law' => 'Law',
        'life' => 'Life',
        'medical' => 'Medical',
        'melodrama' => 'Melodrama',
        'mystery' => 'Mystery',
        'political' => 'Political',
        'romance' => 'Romance',
        'sports' => 'Sports',
        'supernatural' => 'Supernatural',
        'thriller' => 'Thriller',
        'wuxia' => 'Wuxia',
        'youth' => 'Youth',
    ]
];

$active_filters = [];
foreach($_GET as $filter => $values) {
    if(!is_array($values)) {
        $values = [$values];
    }
    $active_filters[$filter] = array_map("htmlspecialchars", $values);
}
    ?>

<main class="container">
    <section class="row justify-content-center">
        <div class="col-lg-12">
            <h2 class="display-5 text-info fw-light">Browse by Drama Category</h2>
            <p class="mb-5">Select any combination of the buttons below to filter the drama by category.</p>

            <?php
                foreach($category_filters as $filter => $option) {
                    $category_name = ucwords($filter);
                    echo "<div class\"mb-3\" role=\"group\" aria-label=\"" .htmlspecialchars($category_name) . " Filter\">";
                    
                    foreach($option as $value => $label) {
                        $is_active = in_array($value, $active_filters[$filter] ?? []);
                        $updated_filters = $active_filters;
                        if($is_active) {
                            $updated_filters[$filter] = array_diff($updated_filters[$filter], [$value]);

                            if(empty($updated_filters[$filter])) {
                                unset($updated_filters[$filter]);
                            }
                        } else {
                            $updated_filters[$filter][] = $value;
                        }
                        $url = $_SERVER['PHP_SELF'] . "?" . http_build_query($updated_filters);
                        echo '<a href="' . htmlspecialchars($url) . '" class="btn ' . ($is_active ? "btn-info" : "btn-outline-info") .' me-3 mb-3">' . htmlspecialchars($label) . '</a>';

                    }
                    
                    echo '</div>';
                }
            
                if(!empty($active_filters)) : ?>
                    <hr>
                    <div class="row">
                        <?php include("includes/filter-category-results.php"); ?>
                    </div>
                <?php endif; ?>
        </div>

        <a href="index.php" class="btn btn-info w-25">Back to the Homepage</a>
    </section>
</main>

<?php
include('includes/footer.php');
?>