<?php
session_start();
require_once('../lib/parsecsv.lib.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    if($_FILES['csv']['error'] == 0 && in_array($_FILES['csv']['type'],$mimes)) {
        try {
            $csvParser = new parseCSV();
            $csvParser->delimiter = $_POST["separator"];
            $csvParser->parse($_FILES['csv']['tmp_name']);
            $_SESSION["data"] = serialize($csvParser);
            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(500);
        }
    } else {
        http_response_code(404);
    }

} else {
    http_response_code(405);
}