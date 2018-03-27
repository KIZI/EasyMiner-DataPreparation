<?php
ini_set('memory_limit', '2048M');
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
            $firstHeads = [];
            $first = true;
            echo "sem tu";
            while ($count == $limit) {
                session_start();
                $csvParser = new parseCSV();
                $csvParser->encoding($_POST["encoding"], "UTF-8");
                if (!$first) {
                    $csvParser->heading = false;
                    $csvParser->fields = $firstHeads;
                }
                $csvParser->limit = $limit;
                $csvParser->offset = $beginning;
                $csvParser->delimiter = $_POST["separator"];
                $csvParser->parse($_FILES['csv']['tmp_name']);
                if ($first) {
                    $firstHeads = $csvParser->titles;
                    $first = false;
                }
                $count = count($csvParser->data);
                $_SESSION["data".$fileNumber] = serialize($csvParser);
                $_SESSION["fileNumber"] = serialize($fileNumber);
                $beginning = $beginning + 1000;
                $_SESSION["loaded"] = serialize($count);
                $fileNumber++;
                session_write_close();
            }
            echo "upload complete";
            http_response_code(200);
        } catch (Exception $e) {
            echo $e;
            http_response_code(500);
        }
    } else {
        http_response_code(404);
    }

}