<?php

/*PREPARED STATEMENT */

//Prepared statement for inserting a new drama
$insert_statement = $connection->prepare("INSERT INTO drama_catalogue(image_filename, title, release_year, episodes, category, original_network, director, screenwriter, cast, description, funfacts, image_url) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);");

//Prepared statement for updating a new drama
$update_statement = $connection->prepare("UPDATE drama_catalogue SET image_filename = ?, title = ?, release_year = ?, episodes = ?, category = ?, original_network = ?, director = ?, screenwriter = ?, cast = ?, description = ?, image_url = ?, funfacts = ? WHERE drama_id = ?;");

//Prepared statement for delete an attraction
$delete_statement = $connection->prepare("DELETE FROM drama_catalogue WHERE drama_id = ?;");

//Prepared statement for selecting all drama
$select_statement = $connection->prepare("SELECT * FROM drama_catalogue;");

//Prepared statement for selecting all drama information based on title
$select_title_statement = $connection->prepare("SELECT * FROM drama_catalogue WHERE title = ?;");

//Prepared statement for selecting all drama information based on its id
$select_id_statement = $connection->prepare("SELECT * FROM drama_catalogue WHERE drama_id = ?;");

//Prepared statement for selecting users based on their username
$select_user_id_statement = $connection->prepare("SELECT * FROM catalogue_admin WHERE account_id = ?;");

//Prepared statement for selecting rating based on drama_id
$select_user_by_drama_id = $connection->prepare("SELECT * FROM user_rating WHERE drama_id = ?");

//Prepared statement for updating users information by id
$update_user = $connection->prepare("UPDATE catalogue_admin SET username = ?, first_name = ?, last_name = ?, email = ?, profile_img = ? WHERE account_id = ?;");

//Prepared statement for insert new data
$insert_review = $connection->prepare("INSERT INTO user_rating SET account_id = ?, drama_id = ?, title = ?, rating = ?, description = ?;");

//Prepared statement for get average rating from user rating table
$avg_rating = $connection->prepare("SELECT AVG(rating) as average_rating FROM user_rating WHERE drama_id = ?;");

//Prepare statement for update average rating from user rating table to the drama catalogue table
$update_avg_rating = $connection->prepare("UPDATE drama_catalogue SET average_rating = ? WHERE drama_id = ?;");

/* FUNCTIONS */
function handle_database_error($statement) {
    global $connection;
    die("Error in " . $statement . ". Error details: " . $connection->error);
}

//function to get all dramas display
function get_all_dramas() {
    global $connection;
    global $select_statement;

    if(!$select_statement->execute()) {
        handle_database_error("fetching dramas");
    } else {
        $result = $select_statement->get_result();
        $dramas = [];
        while($row = $result->fetch_assoc()) {
            $dramas[] = $row;
        }
        return $dramas; 
    } 
}

//function to get the drama by its title
function get_drama_by_title($title) {
    global $connection;
    global $select_title_statement;

    $select_title_statement->bind_param("s", $title);

    if(!$select_title_statement->execute()) {
        handle_database_error("Selecting drama by title");
    }

    $result = $select_title_statement->get_result();
    $specific_drama = $result->fetch_assoc();

    return $specific_drama;
}

//function to get the drama by its id
function get_drama_by_id($id) {
    global $connection;
    global $select_id_statement;

    $select_id_statement->bind_param("i", $id);

    if(!$select_id_statement->execute()) {
        handle_database_error("Selecting drama by ID");
    }

    $result = $select_id_statement->get_result();
    $specific_drama = $result->fetch_assoc();

    return $specific_drama;
}

//function get user by its id
function get_user_profile_by_id($id) {
    global $connection;
    global $select_user_id_statement;

    $select_user_id_statement->bind_param("i", $id);

    if(!$select_user_id_statement->execute()) {
        handle_database_error("Selecting user information by ID");
    }

    $result = $select_user_id_statement->get_result();
    $specific_user = $result->fetch_assoc();

    return $specific_user;
}

//function to get user by drama id
function get_user_by_drama_id($id) {
    global $connection;
    global $select_user_by_drama_id;

    $select_user_by_drama_id->bind_param("i", $id);

    if(!$select_user_by_drama_id->execute()) {
        handle_database_error("Selecting user information by drama ID");
    }

    $result = $select_user_by_drama_id->get_result();
    $specific_user = $result->fetch_assoc();

    return $specific_user;
}

//function to insert the new drama in the database
function insert_drama($image_filename, $title, $release_year, $episodes, $category, $original_network, $director, $screenwriter, $cast, $description, $funfacts, $image_url) {
    global $connection;
    global $insert_statement;

    $category_str = implode(', ', $category);
    $original_network_str = implode(', ', $original_network);

    $insert_statement->bind_param("ssiissssssss",$image_filename, $title, $release_year, $episodes, $category_str, $original_network_str, $director, $screenwriter, $cast, $description, $funfacts ,$image_url);

    if(!$insert_statement->execute()) {
        handle_database_error("Inserting drama.");
    }
}

//function to update the new drama in the database
function update_drama($image_filename, $title, $release_year, $episodes, $category, $original_network, $director, $screenwriter, $cast, $description, $image_url, $funfacts, $drama_id) {
    global $connection;
    global $update_statement;

    $category_str = implode(', ', $category);
    $original_network_str = implode(', ', $original_network);

    $update_statement->bind_param("ssiissssssssi", $image_filename, $title, $release_year, $episodes, $category_str, $original_network_str, $director, $screenwriter, $cast, $description, $image_url, $funfacts, $drama_id);
    $update_statement->execute();
}

//function to update user
function update_user($username, $firstname, $lastname, $email, $profile_img, $account_id) {
    global $connection;
    global $update_user;

    $update_user->bind_param("sssssi", $username, $firstname, $lastname, $email, $profile_img, $account_id);
    $update_user->execute();
}

//function to delete a drama by primary key
function delete_drama($drama_id) {
    global $connection;
    global $delete_statement;

    $delete_statement->bind_param("i", $drama_id);

    if(!$delete_statement->execute()) {
        handle_database_error("Delete Drama");
    }
}

//function to count records
function count_records() {
    global $connection;
    $sql = "SELECT COUNT(*) FROM drama_catalogue";
    $result = mysqli_query($connection, $sql);
    $count = mysqli_fetch_row($result);
    return $count[0];
}

//function for pagination
function pagination_records($limit = 0, $offset = 0) {
    global $connection;
    $sql = "SELECT * FROM drama_catalogue";

    if($limit > 0) {
        $sql .= " LIMIT " . $limit;
    }

    if($offset > 0) {
        $sql .= " OFFSET " .$offset;
    }

    $result = $connection->query($sql);
    return $result;
}

//function to insert the new reviews
function insert_review($account_id, $drama_id, $title, $rating, $description){
    global $connection;
    global $insert_review;

    $insert_review->bind_param("iisds", $account_id, $drama_id, $title, $rating, $description);

    if(!$insert_review->execute()) {
        handle_database_error("Inserting review.");
    }
}

//function to select average rating from user_rating table
function get_avg_rating($drama_id){
    global $connection;
    global $avg_rating;

    $avg_rating->bind_param("i", $drama_id);

    if(!$avg_rating->execute()) {
        handle_database_error("Selecting user information by drama ID");
    }

    $result = $avg_rating->get_result();
    $total_avg_rating = $result->fetch_assoc();

    return $total_avg_rating['average_rating'];
}

//function to update average rating into the drama catalogue
function update_avg_rating($average_rating, $drama_id) {
    global $connection;
    global $update_avg_rating;

    $update_avg_rating->bind_param("di", $average_rating, $drama_id);
    $update_avg_rating->execute();
}
?>