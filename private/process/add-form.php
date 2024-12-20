<?php

// Initialize variables
$title = isset($_POST['title']) ? trim($_POST['title']) : "";
$average_rating = isset($_POST['rating']) ? trim($_POST['rating']) : "";
$release_year = isset($_POST['release-year']) ? trim($_POST['release-year']) : "";
$episodes = isset($_POST['episode']) ? trim($_POST['episode']) : "";
$director = isset($_POST['director']) ? trim($_POST['director']) : "";
$screenwriter = isset($_POST['screenwriter']) ? trim($_POST['screenwriter']) : "";
$cast = isset($_POST['cast']) ? trim($_POST['cast']) : "";
$description = isset($_POST['description']) ? trim($_POST['description']) : "";
$image_url = isset($_POST['img-url']) ? trim($_POST['img-url']) : "";
$funfacts = isset($_POST['funfact']) ? trim($_POST['funfact']) : "";

$original_network = $_POST['network'] ?? array();
$category = $_POST['category'] ?? array();

// Message
$message = '';

// Validation Boolean
$form_good = TRUE;


// Validation for user input into the Add Form
if(isset($_POST['submit'])) {

    // Validation for the drama title
    $title = filter_var($title, FILTER_SANITIZE_STRING);

    if(is_blank($title)) {
        $message .= "<p class=\"text-danger\">Please enter the title of the new drama.</p>";
        $form_good = FALSE;
    } elseif (!has_length_less_than($title, 100)) {
        $message .= "<p class=\"text-danger\">Please enter the title of the new drama within 100 characters.</p>";
        $form_good = FALSE;
    } elseif ($title === null || $title === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid title for the drama.</p>";
        $form_good = FALSE;
    }

    // Validation for release year - number
    if(is_blank($release_year)) {
        $message .= "<p class=\"text-danger\">Please enter the release year of this drama.</p>";
        $form_good = FALSE;
    } elseif (!is_valid_year($release_year)) {
        $message .= "<p class=\"text-danger\">Please enter the valid year format for this drama.</p>";
        $form_good = FALSE;
    } elseif ($release_year === null || $release_year === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid year for this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for the number of episode
    if(is_blank($episodes)) {
        $message .= "<p class=\"text-danger\">Please enter the number of episodes of this drama.</p>";
        $form_good = FALSE;
    } elseif (!is_valid_episode_number($episodes)) {
        $message .= "<p class=\"text-danger\">Please enter the valid number of episode of this drama range from 1 to 99 episodes.</p>";
        $form_good = FALSE;
    } elseif ($episodes === null || $episodes === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid number of episode of this drama.</p>";
    }

    //Validation for the category
    if(is_blank($category)) {
        $message .= "<p class=\"text-danger\">Please select the category of this drama.</p>";
        $form_good = FALSE;
    }

    // Validation for the Original Network
    if(is_blank($original_network)) {
        $message .= "<p class=\"text-danger\">Please enter the original network of this drama.</p>";
        $form_good = FALSE;
    } 

    //Validation for the director
    if(is_blank($director)) {
        $message .= "<p class=\"text-danger\">Please enter the name of the director for this drama.</p>";
        $form_good = FALSE;
    } elseif (!is_letters($director)) {
        $message .= "<p class=\"text-danger\">Please enter the name of the director by letters and spaces only.</p>";
        $form_good = FALSE;
    } elseif (!is_valid_name($director)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each director of this drama.</p>";
        $form_good = FALSE;
    } elseif (has_length_less_than($director, 255)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each director of this drama within 255 characters.</p>";
        $form_good = FALSE;
    } elseif ($director === null || $director === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each director of this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for Screenwriter
    if(is_blank($screenwriter)) {
        $message .= "<p class=\"text-danger\">Please enter the name of the screenwriter for this drama.</p>";
        $form_good = FALSE;
    } elseif (!is_letters($screenwriter)) {
        $message .= "<p class=\"text-danger\">Please enter the name of the screenwriter by letters and spaces only.</p>";
        $form_good = FALSE;
    } elseif (!is_valid_name($screenwriter)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each screenwriter of this drama.</p>";
        $form_good = FALSE;
    } elseif (has_length_less_than($screenwriter, 255)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each screenwriter of this drama within 255 characters.</p>";
        $form_good = FALSE;
    } elseif ($screenwriter === null || $screenwriter === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each screenwriter of this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for Main Cast Name
    if(is_blank($cast)) {
        $message .= "<p class=\"text-danger\">Please enter the name of the main cast for this drama.</p>";
        $form_good = FALSE;
    } elseif (!is_letters($cast)) {
        $message .= "<p class=\"text-danger\">Please enter the name of the main cast by letters and spaces only.</p>";
        $form_good = FALSE;
    } elseif (!is_valid_name($cast)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each main cast of this drama.</p>";
        $form_good = FALSE;
    } elseif (has_length_less_than($cast, 255)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for main cast of this drama within 255 characters.</p>";
        $form_good = FALSE;
    } elseif ($cast === null || $cast === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each main cast of this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for Description
    $description = filter_var($description, FILTER_SANITIZE_STRING);

    if(is_blank($description)) {
        $message .= "<p class=\"text-danger\">Please enter the short description about this drama.</p>";
        $form_good = FALSE;
    } elseif (has_length_less_than($description, 1000)) {
        $message .= "<p class=\"text-danger\">Please enter the short description about this drama within 1000 characters.</p>";
        $form_good = FALSE;
    } elseif ($description === null || $description === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid description for this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for Funfact
    $funfacts = filter_var($funfacts, FILTER_SANITIZE_STRING);
    if (has_length_less_than($funfacts, 1000)) {
        $message .= "<p class=\"text-danger\">Please enter the short funfact about this drama within 1000 characters.</p>";
        $form_good = FALSE;
    }

    //Validation for the URL
    $image_url = filter_var($image_url, FILTER_SANITIZE_URL);

    if(is_blank($image_url)) {
        $message .= "<p class=\"text-danger\">Please enter the url source for the drama image poster.</p>";
        $form_good = FALSE;
    } elseif (!filter_var($image_url, FILTER_VALIDATE_URL)) {
        $message .= "<p class=\"text-danger\">Please enter the valid url source for the drama image poster.</p>";
        $form_good = FALSE;
    }

}

?>