<?php

$sql = "SELECT * FROM drama_catalogue WHERE 1=1";
$type = "";
$parameters = [];
$condition = [];

foreach($active_filters as $filter => $filter_values) {
    if(in_array($filter, ['original_network'])) {
        foreach($filter_values as $value) {
            $condition[] = "$filter LIKE ?";
            $parameters[] = '%' . $value . '%';
        }
        $type .= str_repeat('s', count($filter_values));
    }
}

if(!empty($condition)) {
    $sql .= " AND (" . implode(" OR ", $condition) . ")";
}

$statement = $connection->prepare($sql);
if($statement === FALSE) {
    echo "Failed to prepare the statement: (" . $connection->errno . ")" . $connection->error;
    exit();
}

if($type !== "") {
    $statement->bind_param($type, ...$parameters);
}

if(!$statement->execute()) {
    echo "Execute failed: (" . $statement->errno . ")" . $statement->error;
}

$result = $statement->get_result();
if($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        extract($row);
        echo "
            <div class=\"col-md-10 col-lg-6\">
                <div class=\"row border rounded m-0 flex-md-row mb-4 shadow-sm\">
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
                    <p class=\"card-text mb-auto\">" . ucfirst($category) . "</p>
                    <a href=\"single-view.php?title=" . urlencode($title) . "\" class=\"py-2 btn btn-dark\">View Details</a>
                </div>
                    <div class=\"col-auto d-lg-block pe-0\">
                        <img src=\"img_upload/drama_img/thumbs/$image_filename\" width=\"220\" height=\"300\" alt=\"$title drama poster thumbnail\" class=\"rounded\">
                    </div>
                </div>
            </div>";
    }
}

?>