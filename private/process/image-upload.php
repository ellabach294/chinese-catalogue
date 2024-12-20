<?php

$message = "";
$form_good = TRUE;
$image_filename = "";

if(isset($_POST['submit']) && !empty($_FILES['img-file']) && $form_good) {
    $image_file = $_FILES['img-file'];
    $image_name = $_FILES['img-file']['name'];
    $image_temp_filename = $_FILES['img-file']['tmp_name'];
    $image_size = $_FILES['img-file']['size'];
    $image_error = $_FILES['img-file']['error'];

    $file_extension = explode('.', $image_name);
    $file_extension = strtolower(end($file_extension));

    $allowed = array('jpg', 'jpeg', 'png', 'webp');

    if(in_array($file_extension, $allowed) && $image_error === 0 && $image_size < 2000000) {
        $image_filename = uniqid('', true) . "." . $file_extension;
        $file_destination = __DIR__ .'/../../public/img_upload/drama_img/full/' . $image_filename;
        
        if(!is_dir(__DIR__ .'/../../public/img_upload/drama_img/full/')) {
            mkdir(__DIR__ .'/../../public/img_upload/drama_img/full/', 0777, true);
        }
        if(!is_dir(__DIR__ .'/../../public/img_upload/drama_img/thumbs/')) {
            mkdir(__DIR__ .'/../../public/img_upload/drama_img/thumbs/', 0777, true);
        }

        if(!file_exists($file_destination)) {
            move_uploaded_file($image_temp_filename, $file_destination);

            list($width_original, $height_original) = getimagesize($file_destination);

            $thumb = imagecreatetruecolor(300, 420);
            $smaller_size = min($width_original, $height_original);

            $x_coordinate = ($width_original > $smaller_size) ? ($width_original - $smaller_size) / 2 : 0;
            $y_coordinate = ($height_original > $smaller_size) ? ($height_original - $smaller_size) / 2 : 0;

            switch($file_extension) {
                case 'jpeg':
                case 'jpg':
                    $src_image = imagecreatefromjpeg($file_destination);
                    break;
                case 'png':
                    $src_image = imagecreatefrompng($file_destination);
                    break;
                case 'webp':
                    $src_image = imagecreatefromwebp($file_destination);
                    break;
                default:
                    $message .= "<p class=\"text-danger\">This file type of your post image is not supported.</p>";
                    exit;
            }

            imagecopyresampled($thumb, $src_image, 0, 0, $x_coordinate, $y_coordinate, 300, 420, $smaller_size, $smaller_size);
            
            // save thumbs
            imagejpeg($thumb, __DIR__ .'/../../public/img_upload/drama_img/thumbs/' . $image_filename, 100);

            imagedestroy($thumb);
            imagedestroy($src_image);
        } else {
            $message .= "<p class=\"text-danger\">Your poster image file is too big.</p>";
            $form_good = FALSE;
        }
    } else {
        $message .= "<p class=\"text-danger\">There was an error with this poster image file.</p>";
        $form_good = FALSE;
    }
}


?>