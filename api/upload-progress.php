<?php
ini_set('memory_limit', '2048M');
session_start();
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $count = unserialize($_SESSION["loaded"]);
    $fileN = unserialize($_SESSION["fileNumber"]);
    $message = ($fileN * 1000) + $count;
    http_response_code(200);
    echo $message;
} else {
    http_response_code(404);
}