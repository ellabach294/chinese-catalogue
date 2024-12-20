<?php

function is_blank($value) {
    return !isset($value);
}

function has_length_less_than($value, $max){
    $length = strlen($value);
    return $length <= $max;
}

function is_letters($value) {
    return preg_match("/^[a-zA-Z\s]*$/", $value);
}

function no_spaces($value) {
    return strpos($value, " ") == FALSE;
}

function has_valid_email_format($value) {
    $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9._]+\.[A-Z]{2,}\Z/i';
    return preg_match($email_regex, $value) === 1;
}

function is_valid_year($value) {
    return is_numeric($value) && ($value >= 1900) && ($value <= date("Y") + 5);
}

function is_valid_episode_number($value) {
    return is_numeric($value) && ($value > 0) && ($value <= 99);
}

function is_valid_rating($value) {
    return preg_match("/^\d{1,3}(\.\d{1,2})?$/", $value) && ($value >= 0) && ($value <= 5);
}

function is_5_checkboxes($value, $limit) {
    $selectedValue = count($value);
    return $selectedValue <= $limit;
}

function is_valid_name($value) {
    $valueArray = array_map('trim', explode(',', $value));
    foreach ($valueArray as $name) {
        if(!preg_match('/^[A-Za-z\s,]+$/', $name)) {
            return false;
        }
    }
    return true;
}
?>