<?php
ini_set('memory_limit', '256M');
session_start();
require_once('../lib/parsecsv.lib.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    if($_FILES['csv']['error'] == 0 && in_array($_FILES['csv']['type'],$mimes)) {
        try {
            $uniqName = uniqid('csv_', TRUE);
            $tmpFile = "tmp/$uniqName.csv";
            move_uploaded_file($_FILES['csv']['tmp_name'], $tmpFile);
            $_SESSION["fileName"] = serialize($tmpFile);
            $types = null;
            $_SESSION["types"] = serialize($types);

            $reading = fopen($tmpFile, 'r');
            $writing = fopen($tmpFile.'.tmp', 'w');

            $first = true;
            $firstHeads = [];

            if ($reading) {
                while (($line = fgets($reading)) !== false) {
                    $csvParser = new parseCSV();
                    $csvParser->encoding($_POST["encoding"], "UTF-8");
                    if (!$first) {
                        $csvParser->heading = false;
                        $csvParser->fields = $firstHeads;
                    }
                    $csvParser->delimiter = ",";
                    $csvParser->parse($line);
                    if ($first) {
                        $firstHeads = $csvParser->titles;
                        $first = false;
                        $count = count($csvParser->titles) -1;
                        fputcsv($writing, $firstHeads);
                    }
                    if ($first == false) {
                        foreach ($csvParser->data as $key => $row) {
                            $arrToPush = [];
                            foreach ($csvParser->data[$key] as $val) {
                                array_push($arrToPush, $val);
                            }
                            echo $arrToPush.";";
                            fputcsv($writing, $arrToPush);
                        }
                    }
                }
            }
            fclose($reading); fclose($writing);
            rename($tmpFile.'.tmp', $tmpFile);

            echo $tmpFile;
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