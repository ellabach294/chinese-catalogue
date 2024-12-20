<?php

require_once('../process/connect.php');
$connection = db_connect();

require_once('../process/prepared.php');

$title = "Add a New Drama | The Chinese Drama Catalogue";
include('../includes/header.php');

require('../process/functions.php');
require('../process/add-form.php');
require('../process/image-upload.php');

$message_form = "";
$message = "";

error_reporting(E_ALL);

if (isset($_POST['submit'])) {
    $title = isset($_POST['title']) ? trim($_POST['title']) : "";
    $release_year = isset($_POST['release-year']) ? trim($_POST['release-year']) : "";
    $episodes = isset($_POST['episode']) ? trim($_POST['episode']) : "";
    $director = isset($_POST['director']) ? trim($_POST['director']) : "";
    $screenwriter = isset($_POST['screenwriter']) ? trim($_POST['screenwriter']) : "";
    $cast = isset($_POST['cast']) ? trim($_POST['cast']) : "";
    $description = isset($_POST['description']) ? trim($_POST['description']) : "";
    $funfacts = isset($_POST['funfact']) ? trim($_POST['funfact']) : "";
    $image_url = isset($_POST['img-url']) ? trim($_POST['img-url']) : "";

    $category = $_POST['category'] ?? array();
    $original_network = $_POST['network'] ?? array();

    //Add the new data into database
    if ($form_good === TRUE) {
        $category = $_POST['category'];
        insert_drama($image_filename, $title, $release_year, $episodes, $category, $original_network, $director, $screenwriter, $cast, $description, $funfacts, $image_url);
        $message_form .= "<p class=\"alert alert-success\" role=\"alert\">The new drama added successfully.</p>";

        $image_filename = $title = $average_rating = $release_year = $episodes = $category = $original_network = $director = $screenwriter = $cast = $description = $image_url = $funfacts = '';
    } else {
        $message_form .= "<p class=\"alert alert-danger\" role=\"alert\">There was a problem: " . $connection->error . "</p>";
    }
}

?>

<main class="container">
    <section class="row justify-content-center">
        <div class="col-md-10 col-xl-8 border border-warning border-opacity-25 rounded p-4 mb-2">
            <h2 class="fw-light text-center">Add a New Chinese Drama</h2>
            <p class="lead text-muted mb-3 text-center">To add the new drama details into the catalogue, simply fill in
                the form below and hit "Save".</p>

            <!-- Add Form -->
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">

                <!-- User Message -->
                <?php if (isset($message, $message_form)): ?>
                    <div class="alert" role="alert">
                        <?php
                        echo $message_form;
                        echo $message;
                        ?>
                    </div>
                <?php endif; 
                
                    if(isset($_POST['submit'])) {
                        if($form_good == TRUE) {
                            echo "<div class=\"d-md-flex justify-content-md-end\">";
                            echo "<button class=\"btn btn-warning\"><a href=\"../../public/index.php\" class=\"link-dark\">Back to the Homepage</a></button>";
                            echo "</div>";
                        }
                    }
                ?>

                <!-- Title - text -->
                <div class="mb-3">
                    <label for="title" class="form-label fw-medium lead">Drama Title</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="<?php if (isset($_POST['title']))
                            echo $_POST['title']; ?>" required>
                </div>

                <!-- Release year - number -->
                <div class="mb-3">
                    <label for="release-year" class="form-label fw-medium lead">Release Year</label>
                    <input type="number" name="release-year" id="release_year" class="form-control"
                        value="<?php if (isset($_POST['release-year']))
                            echo $_POST['release-year']; ?>" min="1900"
                        max="2100" required>
                </div>

                <!-- Episodes - number -->
                <div class="mb-3">
                    <label for="episode" class="form-label fw-medium lead">Episodes</label>
                    <input type="number" name="episode" id="episode" class="form-control"
                        value="<?php if (isset($_POST['episode']))
                            echo $_POST['episode']; ?>" min="0" max="100"
                        required>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <p class="fw-medium lead">Category</p>
                    <div class="row ms-3">
                        <?php
                        $category_abbr = [
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
                        ];

                        foreach($category_abbr as $key => $value) {
                            $checked = isset($_POST['category']) && in_array($key, $_POST['category']) ? 'checked' : '';
                            
                            echo "<div class=\"col-sm-6 col-lg-4 mb-2\">";
                            echo "<input class=\"form-check-input\" type=\"checkbox\" value=\"$key\" id=\"$key\" name=\"category[]\" $checked>";
                            echo "<label for=\"$key\" class=\"form-check-label\">$value</label>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <small>You can select multiple category of the drama. Maximum is 5 categories per drama.</small>
                </div>

                <!-- Original Network -->
                <div class="mb-3">
                    <p class="fw-medium lead">Original Network</p>
                    <div class="row ms-3">
                        <?php
                        $network_abbr = [
                            'iQiYi' => 'iQiYi',
                            'Tencent Video' => 'Tencent Video',
                            'Mango TV' => 'Mango TV',
                            'Youku' => 'Youku',
                            'Sohu TV' => 'Sohu TV',
                        ];

                        foreach($network_abbr as $key => $value) {
                            $checked = isset($_POST['network']) && in_array($key, $_POST['network']) ? 'checked' : '';
                            echo "<div class=\"col-md-4 mb-2\">";
                            echo "<input class=\"form-check-input\" type=\"radio\" value=\"$key\" id=\"$key\" name=\"network[]\" $checked>";
                            echo "<label for=\"$key\" class=\"form-check-label\">$value</label>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <small>Please select only 1 main original network.</small>
                </div>

                <!-- Director -->
                <div class="mb-3">
                    <label for="director" class="form-label fw-medium lead">Director</label>
                    <input type="text" name="director" id="director" class="form-control"
                        value="<?php if (isset($_POST['director']))
                            echo $_POST['director']; ?>" required>
                    <small class="fw-light">If the drama has more than one director, please separate each name by the
                        comma. eg: Steve Cheng, Chan Ka Lam</small>
                </div>

                <!-- Screenwriter -->
                <div class="mb-3">
                    <label for="screenwriter" class="form-label fw-medium lead">Screenwriter</label>
                    <input type="text" name="screenwriter" id="screenwriter" class="form-control"
                        value="<?php if (isset($_POST['screenwriter']))
                            echo $_POST['screenwriter']; ?>" required>
                    <small class="fw-light">If the drama has more than one screenwriter, please separate each name by
                        the comma. eg: Steve Cheng, Chan Ka Lam</small>
                </div>

                <!-- Cast -->
                <div class="mb-3">
                    <label for="cast" class="form-label fw-medium lead">Main Cast</label>
                    <input type="text" name="cast" id="cast" class="form-control"
                        value="<?php if (isset($_POST['cast']))
                            echo $_POST['cast']; ?>" required>
                    <small class="fw-light">If the drama has more than one main cast, please separate each name by the
                        comma. eg: Steve Cheng, Chan Ka Lam</small>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-medium lead">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5"><?php if (isset($_POST['description']))
                        echo $_POST['description']; ?></textarea>
                    <small class="fw-light">Please write a short description paragraph about this drama.</small>
                </div>

                <!-- Funfacts -->
                <div class="mb-3">
                    <label for="funfact" class="form-label fw-medium lead">Fun Facts</label>
                    <textarea name="funfact" id="funfact" class="form-control" rows="5"><?php if (isset($_POST['funfact']))
                        echo $_POST['funfact']; ?></textarea>
                    <small class="fw-light">Please write any fun facts that you know about this drama.</small>
                </div>

                <!-- Upload image -->
                <div class="mb-3">
                    <label for="img-file" class="form-label fw-medium lead">Drama Poster Image</label>
                    <input type="file" name="img-file" id="img-file" class="form-control"
                        accept=".jpg, .jpeg, .png, .webp" required>
                </div>

                <!-- Image URL -->
                <div class="mb-3">
                    <label for="img-url" class="form-label fw-medium lead">Poster image URL source</label>
                    <input type="text" name="img-url" id="img-url" class="form-control" value="<?php if (isset($_POST['img-url']))
                        echo $_POST['img-url']; ?>" required>
                </div>

                <!-- Submit -->
                <input type="submit" name="submit" value="Save" class="btn btn-warning">
            </form>
        </div>

    </section>
</main>


<?php
include('../../public/includes/footer.php');
?>