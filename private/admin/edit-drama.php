<?php

require_once('../process/connect.php');
$connection = db_connect();

require_once('../process/prepared.php');

$title = "Edit Drama | The Chinese Drama Catalogue";

include('../includes/header.php');

require('../process/functions.php');
require('../process/edit-form.php');
require('../process/image-upload-edit.php');

$message_form = "";

if(isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $id = '';
}

if(isset($id) && $id !== "") {
    if(is_numeric($id) && $id > 0) {

        $selected_drama = get_drama_by_id($id);

        if($selected_drama) {
            $existing_image_filename = $selected_drama['image_filename'];
            $_SESSION['existing_image_filename'] = $existing_image_filename;
            $existing_title = $selected_drama['title'];
            $existing_release_year = $selected_drama['release_year'];
            $existing_episodes = $selected_drama['episodes'];
            $existing_category = explode(', ',$selected_drama['category']);
            $existing_network = explode(',', $selected_drama['original_network']);
            $existing_director = $selected_drama['director'];
            $existing_screenwriter = $selected_drama['screenwriter'];
            $existing_cast = $selected_drama['cast'];
            $existing_description = $selected_drama['description'];
            $existing_funfact = $selected_drama['funfacts'];
            $existing_img_url = $selected_drama['image_url'];
        } else {
            $message_form .= "<p class=\"alert alert-danger\" role=\"alert\">Sorry, there are no records available that match your selected.</p>";
        }
    }
}

if(isset($_POST['submit'])) {
    if($form_good == TRUE) {
        update_drama($image_filename, $user_title, $user_release_year, $user_episodes, $user_category, $user_network, $user_director, $user_screenwriter, $user_cast, $user_description, $user_img_url, $user_funfact, $id);
        $message_form .= "<p class=\"alert alert-success\" role=\"alert\">". $user_title . " updated successfully!</p>";
        $id = '';
    }
}

?>

<main class="container">
    <section>
        <h2 class="fw-light text-center">Edit Drama</h2>
        <p class="fw-bold display-5 text-warning text-center">
            <?php echo $existing_title; ?>
        </p>
    </section>

    <section class="row justify-content-center">
        <div class="col-md-10 col-xl-8">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST"
                enctype="multipart/form-data">

                <!-- User Message -->
                <?php if (isset($message, $message_form)): ?>
                    <div class="alert" role="alert">
                        <?php
                        echo $message;
                        echo $message_form;

                        ?>
                    </div>
                <?php endif; 

                    if(isset($_POST['submit'])) {
                        if($form_good == TRUE) {
                            echo "<div class=\"d-md-flex justify-content-md-end\">";
                            echo "<button class=\"btn btn-warning\"><a href=\"edit.php\" class=\"link-dark\">Back to the Edit Homepage</a></button>";
                            echo "</div>";
                        }
                    }
                ?>

                <!-- Title - text -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-medium lead">Drama Title</label>
                    <input type="text" name="title" id="title" class="form-control" 
                        value="<?php if($user_title != "") { 
                                        echo $user_title;
                                    } else {
                                        echo $existing_title;
                                    }
                                ?>">
                </div>

                <!-- Release year - number -->
                <div class="mb-3">
                    <label for="release-year" class="form-label fw-medium lead">Release Year</label>
                    <input type="number" name="release-year" id="release_year" class="form-control" 
                        value="<?php if($user_release_year != "") {
                                        echo $user_release_year;
                                    } else {
                                        echo $existing_release_year;
                                    }
                                ?>" 
                        min="1900" max="2100">
                </div>

                <!-- Episodes - number -->
                <div class="mb-3">
                    <label for="episode" class="form-label fw-medium lead">Episodes</label>
                    <input type="number" name="episode" id="episode" class="form-control" 
                        value="<?php if($user_episodes != "") {
                                        echo $user_episodes;
                                    } else {
                                        echo $existing_episodes;
                                    }
                                
                                ?>" 
                        min="0" max="100">
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <p class="fw-medium lead">Category</p>
                    <div class="row ms-3">
                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="action" id="action" name="category[]" <?php echo (in_array("action", $existing_category) || (isset($_POST['category']) && in_array("action", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="action" class="form-check-label">Action</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="adventure" id="adventure" name="category[]" <?php echo (in_array("adventure", $existing_category) || (isset($_POST['category']) && in_array("adventure", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="adventure" class="form-check-label">Adventure</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="business" id="business" name="category[]" <?php echo (in_array("business", $existing_category) || (isset($_POST['category']) && in_array("business", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="business" class="form-check-label">Business</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="comedy" id="comedy" name="category[]" <?php echo (in_array("comedy", $existing_category) || (isset($_POST['category']) && in_array("comedy", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="comedy" class="form-check-label">Comedy</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="drama" id="drama" name="category[]" <?php echo (in_array("drama", $existing_category) || (isset($_POST['category']) && in_array("drama", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="drama" class="form-check-label">Drama</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="family" id="family" name="category[]" <?php echo (in_array("family", $existing_category) || (isset($_POST['category']) && in_array("family", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="family" class="form-check-label">Family</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="fantasy" id="fantasy" name="category[]" <?php echo (in_array("fantasy", $existing_category) || (isset($_POST['category']) && in_array("fantasy", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="fantasy" class="form-check-label">Fantasy</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="historical" id="historical" name="category[]" <?php echo (in_array("historical", $existing_category) || (isset($_POST['category']) && in_array("historical", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="historical" class="form-check-label">Historical</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="law" id="law" name="category[]" <?php echo (in_array("law", $existing_category) || (isset($_POST['category']) && in_array("law", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="law" class="form-check-label">Law</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="life" id="life" name="category[]" <?php echo (in_array("life", $existing_category) || (isset($_POST['category']) && in_array("life", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="life" class="form-check-label">Life</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="medical" id="medical" name="category[]" <?php echo (in_array("medical", $existing_category) || (isset($_POST['category']) && in_array("medical", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="medical" class="form-check-label">Medical</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="melodrama" id="melodrama" name="category[]" <?php echo (in_array("melodrama", $existing_category) || (isset($_POST['category']) && in_array("melodrama", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="melodrama" class="form-check-label">Melodrama</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="mystery" id="mystery" name="category[]" <?php echo (in_array("mystery", $existing_category) || (isset($_POST['category']) && in_array("mystery", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="mystery" class="form-check-label">Mystery</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="political" id="political" name="category[]" <?php echo (in_array("political", $existing_category) || (isset($_POST['category']) && in_array("political", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="political" class="form-check-label">Political</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="romance" id="romance" name="category[]" <?php echo (in_array("romance", $existing_category) || (isset($_POST['category']) && in_array("romance", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="romance" class="form-check-label">Romance</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="supernatural" id="supernatural" name="category[]" <?php echo (in_array("supernatural", $existing_category) || (isset($_POST['category']) && in_array("supernatural", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="supernatural" class="form-check-label">Supernatural</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="thriller" id="thriller" name="category[]" <?php echo (in_array("thriller", $existing_category) || (isset($_POST['category']) && in_array("thriller", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="thriller" class="form-check-label">Thriller</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="wuxia" id="wuxia" name="category[]" <?php echo (in_array("wuxia", $existing_category) || (isset($_POST['category']) && in_array("wuxia", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="wuxia" class="form-check-label">Wuxia</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="youth" id="youth" name="category[]" <?php echo (in_array("youth", $existing_category) || (isset($_POST['category']) && in_array("youth", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="youth" class="form-check-label">Youth</label>
                        </div>

                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="checkbox" value="sports" id="sports" name="category[]" <?php echo (in_array("sports", $existing_category) || (isset($_POST['category']) && in_array("sports", $_POST['category']))) ? "checked" : ""; ?>>
                            <label for="sports" class="form-check-label">Sports</label>
                        </div>
                    </div>
                    <small>You can select multiple category of the drama. Maximum is 5 categories per drama.</small>
                </div>


                <!-- Original Network -->
                <div class="mb-3">
                    <p class="fw-medium lead">Original Network</p>
                    <div class="row ms-3">
                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="radio" value="iQiYi" id="iqiyi" name="network[]" <?php echo (in_array("iQiYi", $existing_network) || (isset($_POST['network']) && in_array("iQiYi", $_POST['network']))) ? "checked" : ""; ?>>
                            <label for="iqiyi" class="form-check-label">iQiYi</label>
                        </div>
    
                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="radio" value="Tencent Video" id="tencent" name="network[]" <?php echo (in_array("Tencent Video", $existing_network) || (isset($_POST['network']) && in_array("Tencent Video", $_POST['network']))) ? "checked" : ""; ?>>
                            <label for="tencent" class="form-check-label">Tencent Video</label>
                        </div>
    
                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="radio" value="Mango TV" id="mangotv" name="network[]" <?php echo (in_array("Mango TV", $existing_network) || (isset($_POST['network']) && in_array("MangoTV", $_POST['network']))) ? "checked" : ""; ?>>
                            <label for="mangotv" class="form-check-label">MangoTV</label>
                        </div>
    
                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="radio" value="Youku" id="youku" name="network[]" <?php echo (in_array("Youku", $existing_network) || (isset($_POST['network']) && in_array("Youku", $_POST['network']))) ? "checked" : ""; ?>>
                            <label for="youku" class="form-check-label">Youku</label>
                        </div>
    
                        <div class="col-sm-6 col-lg-4 mb-2">
                            <input class="form-check-input" type="radio" value="Sohu TV" id="sohutv" name="network[]" <?php echo (in_array("Sohu TV", $existing_network) || (isset($_POST['network']) && in_array("SohuTV", $_POST['network']))) ? "checked" : ""; ?>>
                            <label for="sohutv" class="form-check-label">SohuTV</label>
                        </div>
                    </div>
                    <small class="fw-light">Please select one main network aired this drama. eg: Youku</small>
                </div>

                <!-- Director -->
                <div class="mb-3">
                    <label for="director" class="form-label fw-medium lead">Director</label>
                    <input type="text" name="director" id="director" class="form-control" 
                        value="<?php if($user_director != "") {
                                        echo $user_director;
                                    } else {
                                        echo $existing_director;
                                    }
                                ?>">
                    <small class="fw-light">If the drama has more than one director, please separate each name by the
                        comma. eg: Steve Cheng, Chan Ka Lam</small>
                </div>

                <!-- Screenwriter -->
                <div class="mb-3">
                    <label for="screenwriter" class="form-label fw-medium lead">Screenwriter</label>
                    <input type="text" name="screenwriter" id="screenwriter" class="form-control" 
                        value="<?php if($user_screenwriter != "") {
                                        echo $user_screenwriter;
                                    } else {
                                        echo $existing_screenwriter;
                                    }
                                ?>">
                    <small class="fw-light">If the drama has more than one screenwriter, please separate each name by
                        the comma. eg: Steve Cheng, Chan Ka Lam</small>
                </div>

                <!-- Cast -->
                <div class="mb-3">
                    <label for="cast" class="form-label fw-medium lead">Main Cast</label>
                    <input type="text" name="cast" id="cast" class="form-control" 
                        value="<?php if($user_cast != "") {
                                        echo $user_cast;
                                    } else {
                                        echo $existing_cast;
                                    }
                                ?>">
                    <small class="fw-light">If the drama has more than one main cast, please separate each name by the
                        comma. eg: Steve Cheng, Chan Ka Lam</small>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-medium lead">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5"><?php if($user_description != "") {
                                                                                                        echo $user_description;
                                                                                                    } else {
                                                                                                        echo $existing_description;
                                                                                                    }
                                                                                                ?></textarea>
                    <small class="fw-light">Please write a short description paragraph about this drama.</small>
                </div>

                <!-- Fun Facts-->
                <div class="mb-3">
                    <label for="funfact" class="form-label fw-medium lead">Funfacts</label>
                    <textarea name="funfact" id="funfact" class="form-control" rows="5"><?php if($user_funfact != "") {
                                                                                                        echo $user_funfact;
                                                                                                    } else {
                                                                                                        echo $existing_funfact;
                                                                                                    }
                                                                                                ?></textarea>
                    <small class="fw-light">Please write a short description paragraph about this drama.</small>
                </div>

                <!-- Upload image -->
                <div class="mb-3">
                    <label for="img-file" class="form-label fw-medium lead">Drama Poster Image</label>
                    <input type="file" name="img-file" id="img-file" class="form-control"
                        accept=".jpg, .jpeg, .png, .webp" value="<?php echo $existing_image_filename; ?>">
                </div>

                <!-- Image URL -->
                <div class="mb-3">
                    <label for="img-url" class="form-label fw-medium lead">Poster image URL source</label>
                    <input type="text" name="img-url" id="img-url" class="form-control" 
                        value="<?php if($user_img_url != "") {
                                        echo $user_img_url;
                                    } else {
                                        echo $existing_img_url;
                                    }
                                ?>">
                </div>

                <!-- Hidden value -->
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                    
                <!-- Submit -->
                    <input type="submit" name="submit" value="Save" class="btn btn-warning">

                    <a href="edit.php" class="btn btn-info">Back to the Edit Homepage</a>
            </form>
        </div>
    </section>


</main>


<?php
include('../includes/footer.php');
?>