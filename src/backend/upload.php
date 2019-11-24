<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");

$response = array();
$upload_dir = 'uploads/';
$server_url = 'http://127.0.0.1:8080';

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// var_dump($_FILES['conferences']);

//Validate if the file is receive
if (!$_FILES['conferences']) {
    $response = array(
        "status" => "error",
        "error" => true,
        "message" => "the file is not receive!"
    );
    echo json_encode($response);
    return;
}

//Validate if the file is to big
if ($_FILES['conferences']['size'] > 9434461) {
    $response = array(
        "status" => "error",
        "error" => true,
        "message" => "the file is to big!"
    );
    echo json_encode($response);
    return;
}

//Validate if the file is text plain, and mime type
if ($_FILES['conferences']['type'] != 'text/plain' || mime_content_type($_FILES["conferences"]["tmp_name"]) != 'text/plain') {
    $response = array(
        "status" => "error",
        "error" => true,
        "message" => "the file is not txt!"
    );
    echo json_encode($response);
    return;
}

//upload the received file
$conferences_name = $_FILES["conferences"]["name"];
$conferences_tmp_name = $_FILES["conferences"]["tmp_name"];
$error = $_FILES["conferences"]["error"];

if ($error > 0) {
    $response = array(
        "status" => "error",
        "error" => true,
        "message" => "Error uploading the file conferences-error!"
    );
} else {
    $random_name = uniqid() .  $conferences_name;
    $upload_name = $upload_dir . strtolower($random_name);
    $upload_name = preg_replace('/\s+/', '-', $upload_name);

    if (move_uploaded_file($conferences_tmp_name, getcwd() . '/' . $upload_name)) {
        $response = array(
            "status" => "success",
            "error" => false,
            "message" => "File uploaded successfully",
            "url" => "/" . $upload_name
        );
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "message" => "Error uploading the file move_uploaded_file!"
        );
    }
}
echo json_encode($response);
