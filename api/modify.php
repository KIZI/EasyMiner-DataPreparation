<?php
session_start();
$csvParser = unserialize($_SESSION["data"]);
require_once('../lib/parsecsv.lib.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST["action"] == "toPercent") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(convertToPercentage($_POST["column"], $_POST["parameter"]));

    } elseif ($_POST["action"] == "toPercentNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(convertToPercentageNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    } elseif ($_POST["action"] == "addColumn") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(addColumn($_POST["column"], $_POST["parameter"]));

    } elseif ($_POST["action"] == "addColumnNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(addColumnNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    } elseif ($_POST["action"] == "subtractColumn") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(subtractColumn($_POST["column"], $_POST["parameter"]));

    } elseif ($_POST["action"] == "subtractColumnNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(subtractColumnNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    }

} else {
    http_response_code(405);
}

function convertToPercentage($index, $parameter) {
    $csvParser = unserialize($_SESSION["data"]);
    $dataToSendBack = [];
    foreach ($csvParser->data as $key => $row) {
        $changedValue = round(($row[$index] / $parameter) * 100, 4)."";
        $csvParser->data[$key][$index] = $changedValue;
        if ($dataToSendBack[$changedValue] == null) {
            $dataToSendBack[$changedValue] = 1;
        } else {
            $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
        }
    }
    $_SESSION["data"] = serialize($csvParser);
    return $dataToSendBack;
}

function convertToPercentageNew($index, $parameter, $parameter2) {
    $csvParser = unserialize($_SESSION["data"]);
    $dataToSendBack = [];
    array_push($csvParser->titles, $parameter2);
    foreach ($csvParser->data as $key => $row) {
        $changedValue = round(($row[$index] / $parameter) * 100, 4)."";
        $csvParser->data[$key][$parameter2] = $changedValue;
        if ($dataToSendBack[$changedValue] == null) {
            $dataToSendBack[$changedValue] = 1;
        } else {
            $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
        }
    }
    $e = ['title'=>$parameter2, 'id'=>(count($csvParser->titles) -1)];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $_SESSION["data"] = serialize($csvParser);
    return $answer;
}

function addColumn($index, $parameter) {
    $csvParser = unserialize($_SESSION["data"]);
    $dataToSendBack = [];
    foreach ($csvParser->data as $key => $row) {
        $changedValue = ($row[$index] + $row[$parameter]);
        $csvParser->data[$key][$index] = $changedValue;
        if ($dataToSendBack[$changedValue] == null) {
            $dataToSendBack[$changedValue] = 1;
        } else {
            $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
        }
    }
    $_SESSION["data"] = serialize($csvParser);
    return $dataToSendBack;
}

function addColumnNew($index, $parameter, $parameter2) {
    $csvParser = unserialize($_SESSION["data"]);
    $dataToSendBack = [];
    array_push($csvParser->titles, $parameter2);
    foreach ($csvParser->data as $key => $row) {
        $changedValue = ($row[$index] + $row[$parameter]);
        $csvParser->data[$key][$parameter2] = $changedValue;
        if ($dataToSendBack[$changedValue] == null) {
            $dataToSendBack[$changedValue] = 1;
        } else {
            $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
        }
    }
    $e = ['title'=>$parameter2, 'id'=>(count($csvParser->titles) -1)];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $_SESSION["data"] = serialize($csvParser);
    return $answer;
}

function subtractColumn($index, $parameter) {
    $csvParser = unserialize($_SESSION["data"]);
    $dataToSendBack = [];
    foreach ($csvParser->data as $key => $row) {
        $changedValue = ($row[$index] - $row[$parameter]);
        $csvParser->data[$key][$index] = $changedValue;
        if ($dataToSendBack[$changedValue] == null) {
            $dataToSendBack[$changedValue] = 1;
        } else {
            $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
        }
    }
    $_SESSION["data"] = serialize($csvParser);
    return $dataToSendBack;
}

function subtractColumnNew($index, $parameter, $parameter2) {
    $csvParser = unserialize($_SESSION["data"]);
    $dataToSendBack = [];
    array_push($csvParser->titles, $parameter2);
    foreach ($csvParser->data as $key => $row) {
        $changedValue = ($row[$index] - $row[$parameter]);
        $csvParser->data[$key][$parameter2] = $changedValue;
        if ($dataToSendBack[$changedValue] == null) {
            $dataToSendBack[$changedValue] = 1;
        } else {
            $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
        }
    }
    $e = ['title'=>$parameter2, 'id'=>(count($csvParser->titles) -1)];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $_SESSION["data"] = serialize($csvParser);
    return $answer;
}