<?php
session_start();
require_once('../lib/parsecsv.lib.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(dataToSend());
} else {
    http_response_code(405);
}

function dataToSend() {
    $csvParser = unserialize($_SESSION["data"]);
    $answerTitles = [];
    $i = 0;
    foreach ($csvParser->titles as $value) {
        $e = ['id' => $i, 'title' => $value];
        array_push($answerTitles, $e);
        $i++;
    }
    $unique = [];
    $count = 0;
    foreach ($csvParser->data as $key => $row) {
        $count++;
        $k = 0;
        foreach ($row as $item) {
            if ($unique[$k][$item.""] == null) {
                $unique[$k][$item.""] = 1;
            } else {
                $unique[$k][$item.""] = $unique[$k][$item.""] + 1;
            }
            $k++;
        }
    }
    $toReturn = ["titles"=>$answerTitles, "rows"=>$unique, "count"=>$count];
    return $toReturn;
}