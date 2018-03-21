<?php
session_start();
require_once('../lib/parsecsv.lib.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    if($_FILES['csv']['error'] == 0 && in_array($_FILES['csv']['type'],$mimes)) {
        try {
            $limit = 1000;
            $beginning = 0;
            $fileNumber = 0;
            $count = 1000;
            $types = null;
            $_SESSION["types"] = serialize($types);
            while ($count == $limit) {
                $csvParser = new parseCSV();
                $csvParser->limit = $limit;
                $csvParser->offset = $beginning;
                $csvParser->delimiter = $_POST["separator"];
                $csvParser->parse($_FILES['csv']['tmp_name']);
                $count = count($csvParser->data);
                $_SESSION["data".$fileNumber] = serialize($csvParser);
                $_SESSION["fileNumber"] = serialize($fileNumber);
                $beginning = $beginning + 1000;
                echo json_encode(["loaded" => $beginning, "count" => $count, "fileNumber" => $fileNumber]);
                $fileNumber++;
            }
            http_response_code(200);
        } catch (Exception $e) {
            echo $e;
            http_response_code(500);
        }
    } else {
        http_response_code(404);
    }

}

function send_message($message) {

    echo json_encode($message) . PHP_EOL;
    echo PHP_EOL;

    ob_flush();
    flush();
}