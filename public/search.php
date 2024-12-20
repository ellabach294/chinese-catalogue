<?php

require_once('../private/process/connect.php');
$connection = db_connect();

require_once('../private/process/prepared.php');

$title = "Advanced Search | The Chinese Drama Catalogue";
include('includes/header.php');

$keyword_search = isset($_GET['keyword-search']) ? $_GET['keyword-search'] : '';
$title_search = isset($_GET['title-search']) ? $_GET['title-search'] : '';
$category_search = isset($_GET['category']) ? $_GET['category'] : '';
$network_search = isset($_GET['network']) ? $_GET['network'] : '';

$sortingColumn = isset($_GET['column-sort']) ? $_GET['column-sort'] : '';
$sortingOrder = isset($_GET['order-sort']) ? $_GET['order-sort'] : '';

?>

<main class="container">
    <section class="row justify-content-center mb-5">
        <div class="col col-md-10 col-xl-8">
            <h2 class="display-5 mb-5">Advanced Search</h2>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET"
            class="mb-5 p-3 rounded col col-md-10 col-xl-8">

            <!-- Search for Keyword -->
            <fieldset class="mb-4">
                <label for="keyword-search" class="fs-5 form-label lead">Search for: </label>
                <input type="search" class="form-control" name="keyword-search" id="keyword-search"
                    value="<?php echo isset($_GET['keyword-search']) ? $_GET['keyword-search'] : '' ?>"
                    placeholder="Enter keywords...">
            </fieldset>

            <!-- Search by title -->
            <fieldset class="mb-4">
                <label for="title-search" class="fs-5 form-label lead">Search by Title:</label>
                <input type="search" class="form-control" name="title-search" id="title-search"
                    value="<?php echo isset($_GET['title-search']) ? $_GET['title-search'] : '' ?>"
                    placeholder="Enter the title...">
            </fieldset>

            <!-- Search by Original Network -->
            <fieldset class="mb-3">
                <p class="fw-medium lead">Original Network</p>
                <div class="row ms-3">
                    <div class="col-sm-6 col-lg-4 mb-2">
                        <input class="form-check-input" type="checkbox" value="iQiYi" id="iqiyi" name="network[]" <?php if (isset($_GET['network']) && in_array("iQiYi", $_GET['network']))
                            echo 'checked'; ?>>
                        <label for="iqiyi" class="form-check-label">iQiYi</label>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-2">
                        <input class="form-check-input" type="checkbox" value="Tencent Video" id="tencent" name="network[]"
                            <?php if (isset($_GET['network']) && in_array("Tencent Video", $_GET['network']))
                                echo 'checked'; ?>>
                        <label for="tencent" class="form-check-label">Tencent Video</label>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-2">
                        <input class="form-check-input" type="checkbox" value="Mango TV" id="mangotv" name="network[]"
                            <?php if (isset($_GET['network']) && in_array("Mango TV", $_GET['network']))
                                echo 'checked'; ?>>
                        <label for="mangotv" class="form-check-label">MangoTV</label>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-2">
                        <input class="form-check-input" type="checkbox" value="Youku" id="youku" name="network[]" <?php if (isset($_GET['network']) && in_array("Youku", $_GET['network']))
                            echo 'checked'; ?>>
                        <label for="youku" class="form-check-label">Youku</label>
                    </div>

                    <div class="col-sm-6 col-lg-4 mb-2">
                        <input class="form-check-input" type="checkbox" value="Sohu TV" id="sohutv" name="network[]" <?php if (isset($_GET['network']) && in_array("Sohu TV", $_GET['network']))
                            echo 'checked'; ?>>
                        <label for="sohutv" class="form-check-label">SohuTV</label>
                    </div>
                </div>
            </fieldset>

            <!-- Sort by Des or Asc -->
            <fieldset class="mb-4">
                <legend class="fs-5 lead" class="form-label">Sorting Order</legend>
                <div class="form-check my-3">
                    <select name="column-sort" id="column-sort" aria-describedby="column-help"
                        class="custom-select form-control">
                        <option value="title" <?php if (isset($_GET['column-sort']) && $_GET['column-sort'] === 'title') {
                            echo 'selected';
                        } ?>>Sort by Title</option>
                        <option value="release_year" <?php if (isset($_GET['column-sort']) && $_GET['column-sort'] === 'release_year') {
                            echo 'selected';
                        } ?>>Sort by Year</option>
                    </select>
                </div>

                <div class="form-check">
                    <input type="radio" name="order-sort" id="asc" value="asc" <?php if (
                        isset($_GET['order-sort']) && $_GET['order-sort'] === 'asc'
                    )
                        echo 'checked'; ?>>
                    <label for="asc" class="form=check-label">Ascending (A-Z, 0-9)</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="order-sort" id="desc" value="desc" <?php if (isset($_GET['order-sort']) && $_GET['order-sort'] === 'desc')
                        echo 'checked'; ?>>
                    <label for="desc" class="form=check-label">Descending (Z-A, 9-0)</label>
                </div>
            </fieldset>

            <div class="mb-4">
                <input type="submit" value="Search" name="submit" id="submit" class="btn btn-warning">
            </div>
        </form>

        <!-- Result Search Table display -->

        <?php

            $query = "SELECT * FROM drama_catalogue WHERE 1=1";
            $parameters = [];
            $conditions = [];
            $types = '';

            if(isset($_GET['submit'])) {

                // Keyword search
                if(!empty($keyword_search)) {
                    $columns = ['title', 'category', 'original_network', 'director', 'screenwriter', 'cast'];
                    foreach($columns as $column) {
                        $condition[] = "$column LIKE ?";
                        $parameters[] = '%' . $keyword_search . '%';
                    }

                    $types .= str_repeat('s', count($columns));

                    if(!empty($condition)) {
                        $query .= " AND (" . implode(" OR ", $condition) . ")";
                    }
                }

                //Title search
                if(!empty($title_search)) {
                    $query .= " AND title LIKE CONCAT('%', ?, '%')";
                    $parameters[]= $title_search;
                    $types .= 's';
                }

                //Original Network search
                if(!empty($network_search)) {
                    $placeholders = implode(", ", array_fill(0, count($network_search), '?'));
                    $query .= " AND original_network IN ($placeholders)";
                    $parameters = array_merge($parameters, $network_search);
                    $types .= str_repeat('s', count($network_search));
                }

                //Sorting
                if(!empty($sortingColumn) && !empty($sortingOrder)) {
                    $columns = ['title', 'release_year'];
                    if(in_array($sortingColumn, $columns)) {
                        $condition = $sortingOrder == 'asc' ? 'ASC' : 'DESC';
                        $query .= " ORDER BY $sortingColumn $sortingOrder";
                    } else {
                        echo "<p class=\"alert alert-danger\">Invalid Column Sort</p>";
                        exit();
                    }
                }

                //Prepare statement and execute the query statement
                if($statement = $connection->prepare($query)) {
                    if($types !== "") {
                        $statement->bind_param($types, ...$parameters);
                    }

                    // Debugging
                    // echo "<p>Debug - SQL Query:" . $query ."</p>";

                    $statement->execute();
                    $result = $statement->get_result();

                    if($result->num_rows > 0) {
                        echo "<div class=\"table-responsive-md\">
                            <table class=\"table table-bordered\"> 
                                <thead>
                                    <tr class=\"bg-warning text-light\">
                                        <th scope=\"col\">Title</th>
                                        <th scope=\"col\">Release Year</th>
                                        <th scope=\"col\">Original Network</th>
                                        <th scope=\"col\">Category</th>
                                        <th scope=\"col\">Director</th>
                                        <th scope=\"col\">Screenwriter</th>
                                        <th scope=\"col\">Cast</th>
                                        <th scope=\"col\">More Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ";
                        while($row = $result->fetch_assoc()) {
                            extract($row);

                            echo "<tr>
                                    <td>$title</td>
                                    <td>$release_year</td>
                                    <td>$original_network</td>
                                    <td>". ucfirst($category). "</td>
                                    <td>$director</td>
                                    <td>$screenwriter</td>
                                    <td>$cast</td>
                                    <td><a href=\"single-view.php?title=" . urlencode($title) . "&drama_id=" . urlencode($drama_id) . "\">View Details</a></td>
                                </tr>
                                ";
                        }
                        echo '</tbody>';
                        echo "</table>";
                        echo "</div>";
                    } else {
                        echo "<p>Sorry! There are no records available.</p>";
                    }
                }
            }
        
        ?>

    </section>

</main>

<?php
include('includes/footer.php');
?>