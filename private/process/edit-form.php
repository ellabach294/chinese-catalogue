<?php

// Initialize variables
$user_title = isset($_POST['title']) ? trim($_POST['title']) : "";
$user_release_year = isset($_POST['release-year']) ? trim($_POST['release-year']) : "";
$user_episodes = isset($_POST['episode']) ? trim($_POST['episode']) : "";
$user_director = isset($_POST['director']) ? trim($_POST['director']) : "";
$user_screenwriter = isset($_POST['screenwriter']) ? trim($_POST['screenwriter']) : "";
$user_cast = isset($_POST['cast']) ? trim($_POST['cast']) : "";
$user_description = isset($_POST['description']) ? trim($_POST['description']) : "";
$user_funfact = isset($_POST['funfact']) ? trim($_POST['funfact']) : "";
$user_img_url = isset($_POST['img-url']) ? trim($_POST['img-url']) : "";

$user_category = $_POST['category'] ?? array();
$user_network = $_POST['network'] ?? array();

// Message
$message = '';

// Validation Boolean
$form_good = TRUE;


// Validation for user input into the Add Form
if(isset($_POST['submit'])) {

    // Validation for the drama title
    $user_title = filter_var($user_title, FILTER_SANITIZE_STRING);

    if (!has_length_less_than($user_title, 100)) {
        $message .= "<p class=\"text-danger\">Please enter the title of the new drama within 100 characters.</p>";
        $form_good = FALSE;
    } elseif ($user_title === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid title for the drama.</p>";
        $form_good = FALSE;
    }
    
    // Validation for release year - number
    if (!is_valid_year($user_release_year)) {
        $message .= "<p class=\"text-danger\">Please enter the valid year format for this drama.</p>";
        $form_good = FALSE;
    } elseif ($user_release_year === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid year for this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for the number of episode
    if (!is_valid_episode_number($user_episodes)) {
        $message .= "<p class=\"text-danger\">Please enter the valid number of episode of this drama range from 1 to 99 episodes.</p>";
        $form_good = FALSE;
    } elseif ($user_episodes === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid number of episode of this drama.</p>";
    }

    //Validation for the director
    if (!is_letters($user_director)) {
        $message .= "<p class=\"text-danger\">Please enter the name of the director by letters and spaces only.</p>";
        $form_good = FALSE;
    } elseif (!is_valid_name($user_director)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each director of this drama.</p>";
        $form_good = FALSE;
    } elseif (has_length_less_than($user_director, 255)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each director of this drama within 255 characters.</p>";
        $form_good = FALSE;
    } elseif ($user_director === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each director of this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for Screenwriter
    if (!is_letters($user_screenwriter)) {
        $message .= "<p class=\"text-danger\">Please enter the name of the screenwriter by letters and spaces only.</p>";
        $form_good = FALSE;
    } elseif (!is_valid_name($user_screenwriter)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each screenwriter of this drama.</p>";
        $form_good = FALSE;
    } elseif (has_length_less_than($user_screenwriter, 255)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each screenwriter of this drama within 255 characters.</p>";
        $form_good = FALSE;
    } elseif ($user_screenwriter === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each screenwriter of this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for Main Cast Name
    if (!is_letters($user_cast)) {
        $message .= "<p class=\"text-danger\">Please enter the name of the main cast by letters and spaces only.</p>";
        $form_good = FALSE;
    } elseif (!is_valid_name($user_cast)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each main cast of this drama.</p>";
        $form_good = FALSE;
    } elseif (has_length_less_than($user_cast, 255)) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for main cast of this drama within 255 characters.</p>";
        $form_good = FALSE;
    } elseif ($user_cast === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid name for each main cast of this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for Description
    $user_description = filter_var($user_description, FILTER_SANITIZE_STRING);

    if (has_length_less_than($user_description, 1000)) {
        $message .= "<p class=\"text-danger\">Please enter the short description about this drama within 1000 characters.</p>";
        $form_good = FALSE;
    } elseif ($user_description === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid description for this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for Description
    $user_funfact = filter_var($user_funfact, FILTER_SANITIZE_STRING);

    if (has_length_less_than($user_description, 1000)) {
        $message .= "<p class=\"text-danger\">Please enter the short description about this drama within 1000 characters.</p>";
        $form_good = FALSE;
    } elseif ($user_description === FALSE) {
        $message .= "<p class=\"text-danger\">Please enter the valid description for this drama.</p>";
        $form_good = FALSE;
    }

    //Validation for the URL
    $user_img_url = filter_var($user_img_url, FILTER_SANITIZE_URL);

    if (!filter_var($user_img_url, FILTER_VALIDATE_URL)) {
        $message .= "<p class=\"text-danger\">Please enter the valid url source for the drama image poster.</p>";
        $form_good = FALSE;
    }

}

?>