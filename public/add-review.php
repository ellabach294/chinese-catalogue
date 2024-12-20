<?php

require_once('../private/process/connect.php');
$connection = db_connect();

require_once('../private/process/prepared.php');

$title = "Add Reviews | The Chinese Drama Catalogue";
include('includes/header.php');

include('../private/process/functions.php');

$message = "";
$message_form = "";

$form_good = true;

$account_id = $_SESSION['id'];
if(isset($_SESSION['rating_id'])) {
    $review_id = (int)$_SESSION['rating_id'];
}else {
    $review_id = null;
}

if(isset($_GET['drama_id'])) {
    $_SESSION['drama_id'] = $_GET['drama_id'];
}

if(isset($_SESSION['drama_id'])) {
    $drama_id = (int)$_SESSION['drama_id'];
} else {
    $drama_id = null;
}


if(isset($_POST['submit'])) {
    $review_title = isset($_POST['review-title']) ? trim($_POST['review-title']) : "";
    $rating = $_POST['rating'] ?? array();
    $description = isset($_POST['review-description']) ? trim($_POST['review-description']) : "";

    if(is_blank($review_title)) {
        $message .= "<p class=\"text-danger\">Please enter the title for your review.</p>";
        $form_good = false;
    } elseif(!has_length_less_than($review_title, 100)) {
        $message .= "<p class=\"text-danger\">Please enter the title for your review within 100 characters.</p>";
        $form_good = false;
    }

    // Rating
    $rating = filter_var($rating, FILTER_VALIDATE_INT);

    if(is_blank($rating)) {
        $message .= "<p class=\"text-danger\">Please enter your rating for this drama.</p>";
        $form_good = false;
    } 

    // Review Description
    // $description = filter_var($description, FILTER_SANITIZE_STRING);

    if(is_blank($description)) {
        $message .= "<p class=\"text-danger\">Please enter the short review description.</p>";
        $form_good = FALSE;
    } elseif (!has_length_less_than($description, 255)) {
        $message .= "<p class=\"text-danger\">Please enter the short review description about this drama within 255 characters.</p>";
        $form_good = FALSE;
    } 

    if($form_good === TRUE) {
        insert_review($account_id, $drama_id, $review_title, $rating, $description);
        $message_form .= "<p class=\"alert alert-success\" role=\"alert\">The new review added successfully.</p>";
        $average_rating = get_avg_rating($drama_id);
        update_avg_rating($average_rating, $drama_id);

    } else {
        $message_form .= "<p class=\"alert alert-danger\" role=\"alert\">There was a problem: " . $connection->error . "</p>";
    }
}

?>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-xl-8">
            <h2 class="text-center text-warning">Post Your Review</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <!-- User add message -->
                <?php if (isset($message, $message_form)): ?>
                    <div class="alert" role="alert">
                        <?php
                        echo $message_form;
                        echo $message;
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Review Title -->
                <div class="mb-3">
                    <label for="review-title" class="form-label fw-medium lead">Review Title</label>
                    <input type="text" name="review-title" id="review-title" class="form-control" value="<?php if (isset($_POST['review-title']))
                        echo $_POST['review-title']; ?>"required>
                </div>

                <!-- Rating -->
                <div class="mb-3">
                    <label for="rating" class="form-label fw-medium lead">Review Rating</label>
                    <select name="rating" id="rating" class="custom-select form-control" required>
                        <option value="0" <?php if (isset($_POST['rating']) && $_POST['rating'] == "0") {
                            echo "selected";
                        } ?>>0</option>
                        <option value="1" <?php if (isset($_POST['rating']) && $_POST['rating'] == "1") {
                            echo "selected";
                        } ?>>1</option>
                        <option value="2" <?php if (isset($_POST['rating']) && $_POST['rating'] == "2") {
                            echo "selected";
                        } ?>>2</option>
                        <option value="3" <?php if (isset($_POST['rating']) && $_POST['rating'] == "3") {
                            echo "selected";
                        } ?>>3</option>
                        <option value="4" <?php if (isset($_POST['rating']) && $_POST['rating'] == "4") {
                            echo "selected";
                        } ?>>4</option>
                        <option value="5" <?php if (isset($_POST['rating']) && $_POST['rating'] == "5") {
                            echo "selected";
                        } ?>>5</option>
                    </select>
                    <small>Please rate this drama which 0 is extremely bad and 5 is extremely good.</small>
                </div>

                <!-- Review -->
                <div class="mb-3">
                    <label for="review-description" class="form-label fw-medium lead">Description</label>
                    <textarea type="text" name="review-description" id="review-description" class="form-control"
                        required><?php if (isset($_POST['review-description']))
                            echo $_POST['review-description']; ?></textarea>
                </div>

                <input type="hidden" name="drama_id" id="drama_id" value="<?php echo $drama_id; ?>">
                <input type="hidden" name="account_id" id="account_id" value="<?php echo $account_id; ?>">
                <input type="hidden" name="review_id" id="review_id" value="<?php echo $review_id; ?>">

                <input type="submit" value="Submit Review" name="submit" class="btn btn-warning">
                <a href="index.php" class="btn btn-info">Back to the Homepage</a>
            </form>
        </div>
    </div>
</main>

<?php

include('includes/footer.php');

?>