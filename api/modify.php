<?php
ini_set('memory_limit', '256M');
date_default_timezone_set('Europe/Prague');
session_start();
require_once('../lib/parsecsv.lib.php');

foreach (glob("tmp/*.csv") as $file) {
    if(time() - filectime($file) > 86400){
        unlink($file);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST["action"] == "toPercentNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(convertToPercentageNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"], $_POST["parameter3"]));

    } elseif ($_POST["action"] == "addColumnNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(addColumnNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    } elseif ($_POST["action"] == "subtractColumnNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(subtractColumnNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    } elseif ($_POST["action"] == "removeNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(removeNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"], $_POST["parameter3"]));

    } elseif ($_POST["action"] == "regExNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(regExNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    } elseif ($_POST["action"] == "regExRepNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(regExRepNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"], $_POST["parameter3"]));

    } elseif ($_POST["action"] == "toDaysNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(toDaysNew($_POST["column"], $_POST["parameter"]));

    } elseif ($_POST["action"] == "toTimestampNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(toTimestampNew($_POST["column"], $_POST["parameter"]));

    } elseif ($_POST["action"] == "expressionNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(exprNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    }elseif ($_POST["action"] == "diffDateNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(divDayNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    }elseif ($_POST["action"] == "joinTextNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(joinTextNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"], $_POST["parameter3"]));

    } elseif($_POST["action"] == "delete") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(delete($_POST["column"]));

    } elseif($_POST["action"] == "changeType") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(changeType($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    }else {
        http_response_code(405);
    }

} else {
    http_response_code(405);
}

function convertToPercentageNew($index, $parameter, $parameter2, $parameter3) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }
    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Numeric") {
            $answer = ["msg"=>"Selected column is not numeric type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    if (!is_numeric($parameter)) {
        $answer = ["msg"=>"Divisor is not a number"];
        return $answer;
    }

    if (!is_numeric($parameter3)) {
        $answer = ["msg"=>"Round precision have to be number"];
        return $answer;
    }

    if (is_numeric($parameter3)) {
        if ($parameter3 < 0 || $parameter3 > 30) {
            $answer = ["msg"=>"Round precision have to be between 0 and 30"];
            return $answer;
        }
    }

    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    try {
                        $changedValue = round(($row[$index] / $parameter) * 100, (int)$parameter3)."";
                    } catch (Exception $er) {
                        $changedValue = 0;
                    }
                    $csvParser->data[$key][$parameter2] = $changedValue;
                    if ($dataToSendBack[$changedValue] == null) {
                        $dataToSendBack[$changedValue] = 1;
                    } else {
                        $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $countAll = 0;
    foreach ($dataToSendBack as $item => $z) {
        if (is_numeric($item)) {
            $countAll = $countAll + $item;
        }
    }

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e, "type" => ["type" => "Numeric", "count" => $countAll]];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Numeric", "count" => $countAll]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function addColumnNew($index, $parameter, $parameter2) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Numeric") {
            $answer = ["msg"=>"Selected column is not numeric type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    if (in_array($parameter, $titles)) {
        $type = array_search($parameter, $titles);
        if ($types[$type]["type"] != "Numeric") {
            $answer = ["msg"=>"Second column is not numeric type"];
            return $answer;
        }
    }


    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    if (is_numeric($row[$index]) && is_numeric($row[$parameter])) {
                        $changedValue = ($row[$index] + $row[$parameter]);
                    } else {
                        $changedValue = 0;
                    }
                    $csvParser->data[$key][$parameter2] = $changedValue;
                    if ($dataToSendBack[$changedValue] == null) {
                        $dataToSendBack[$changedValue] = 1;
                    } else {
                        $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $countAll = 0;
    foreach ($dataToSendBack as $item => $z) {
        if (is_numeric($item)) {
            $countAll = $countAll + $item;
        }
    }

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e, "type" => ["type" => "Numeric", "count" => $countAll]];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Numeric", "count" => $countAll]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function subtractColumnNew($index, $parameter, $parameter2) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Numeric") {
            $answer = ["msg"=>"Selected column is not numeric type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    if (in_array($parameter, $titles)) {
        $type = array_search($parameter, $titles);
        if ($types[$type]["type"] != "Numeric") {
            $answer = ["msg"=>"Second column is not numeric type"];
            return $answer;
        }
    }

    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    if (is_numeric($row[$index]) && is_numeric($row[$parameter])) {
                        $changedValue = ($row[$index] - $row[$parameter]);
                    } else {
                        $changedValue = 0;
                    }
                    $csvParser->data[$key][$parameter2] = $changedValue;
                    if ($dataToSendBack[$changedValue] == null) {
                        $dataToSendBack[$changedValue] = 1;
                    } else {
                        $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $countAll = 0;
    foreach ($dataToSendBack as $item => $z) {
        if (is_numeric($item)) {
            $countAll = $countAll + $item;
        }
    }

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e, "type" => ["type" => "Numeric", "count" => $countAll]];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Numeric", "count" => $countAll]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function removeNew($index, $parameter, $parameter2, $parameter3) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Text") {
            $answer = ["msg"=>"Selected column is not text type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    if (!$parameter || $parameter == "") {
        $answer = ["msg"=>"No search value selected"];
        return $answer;
    } else {
        if (strlen($parameter) > 250) {
            $answer = ["msg"=>"Search parameter can be only 250 characters long"];
            return $answer;
        }
    }

    if (strlen($parameter3) > 250) {
        $answer = ["msg"=>"Replace parameter can be only 250 characters long"];
        return $answer;
    }

    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);
    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    $changedValue = str_replace($parameter, $parameter3, $row[$index]);
                    $csvParser->data[$key][$parameter2] = $changedValue;
                    if ($dataToSendBack[$changedValue] == null) {
                        $dataToSendBack[$changedValue] = 1;
                    } else {
                        $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function regExNew($index, $parameter, $parameter2) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Text") {
            $answer = ["msg"=>"Selected column is not numeric type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    if (!$parameter || $parameter == "") {
        $answer = ["msg"=>"No search expression selected"];
        return $answer;
    } else {
        if (strlen($parameter) > 250) {
            $answer = ["msg"=>"Search parameter can be only 250 characters long"];
            return $answer;
        }

        if (preg_match($parameter, null) === false) {
            $answer = ["msg"=>"Search expression is not valid regular expression"];
            return $answer;
        }
    }

    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    preg_match($parameter, $row[$index], $matches);
                    $changedValue = $matches[0];
                    if ($dataToSendBack[$changedValue] == null) {
                        $dataToSendBack[$changedValue] = 1;
                    } else {
                        $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function regExRepNew($index, $parameter, $parameter2, $parameter3) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Text") {
            $answer = ["msg"=>"Selected column is not text type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    if (!$parameter || $parameter == "") {
        $answer = ["msg"=>"No search expression selected"];
        return $answer;
    } else {
        if (strlen($parameter) > 250) {
            $answer = ["msg"=>"Search parameter can be only 250 characters long"];
            return $answer;
        }

        if (preg_match($parameter, null) === false) {
            $answer = ["msg"=>"Search expression is not valid regular expression"];
            return $answer;
        }
    }

    if (strlen($parameter3) > 250) {
        $answer = ["msg"=>"Replace parameter can be only 250 characters long"];
        return $answer;
    }

    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    $changedValue = preg_replace($parameter, $parameter3, $row[$index]);
                    $csvParser->data[$key][$parameter2] = $changedValue;
                    if ($dataToSendBack[$changedValue] == null) {
                        $dataToSendBack[$changedValue] = 1;
                    } else {
                        $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function joinTextNew($index, $parameter, $parameter2, $parameter3) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Text") {
            $answer = ["msg"=>"Selected column is not text type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    if (in_array($parameter, $titles)) {
        $type = array_search($parameter, $types);
        if ($types[$type]["type"] != "Text") {
            $answer = ["msg"=>"Second column is not numeric type"];
            return $answer;
        }
    }

    if (strlen($parameter3) > 250) {
        $answer = ["msg"=>"Replace parameter can be only 250 characters long"];
        return $answer;
    }

    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    $changedValue = ("".$row[$index].$parameter3.$row[$parameter]);
                    $csvParser->data[$key][$parameter2] = $changedValue;
                    if ($dataToSendBack[$changedValue] == null) {
                        $dataToSendBack[$changedValue] = 1;
                    } else {
                        $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function toDaysNew($index, $parameter2) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Date") {
            $answer = ["msg"=>"Selected column is not date type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                $keyF = array_search($index, $csvParser->titles);
                $datetime = new DateTime();
                $types = unserialize($_SESSION["DATAPREP-types"]);
                $format = $types[$keyF]['format'];
                foreach ($csvParser->data as $key => $row) {
                    $newDate = $datetime->createFromFormat($format, $row[$index]);
                    if ($newDate) {
                        $changedValue = date_format($newDate, 'l');
                    } else {
                        $changedValue = "";
                    }
                    $csvParser->data[$key][$parameter2] = $changedValue;
                    if ($dataToSendBack[$changedValue] == null) {
                        $dataToSendBack[$changedValue] = 1;
                    } else {
                        $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function divDayNew($index, $index2, $parameter2) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Date") {
            $answer = ["msg"=>"Selected column is not date type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    if (in_array($index2, $titles)) {
        $type = array_search($index2, $titles);
        if ($types[$type]["type"] != "Date") {
            $answer = ["msg"=>"Second column is not date type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                $keyF = array_search($index, $csvParser->titles);
                $keyF2 = array_search($index2, $csvParser->titles);
                $datetime = new DateTime();
                $types = unserialize($_SESSION["DATAPREP-types"]);
                $format = $types[$keyF]['format'];
                $format2 = $types[$keyF2]['format'];
                foreach ($csvParser->data as $key => $row) {
                    $newDate = $datetime->createFromFormat($format, $row[$index]);
                    $newDate2 = $datetime->createFromFormat($format2, $row[$index2]);
                    if ($newDate && $newDate2) {
                        $seconds = date_timestamp_get($newDate) - date_timestamp_get($newDate2);
                        if ($seconds == 0) {
                            $toReturn = "0 s";
                        } else {
                            $days = floor($seconds / (3600 * 24));
                            $seconds -= $days * 3600 * 24;
                            $hrs = floor($seconds / 3600);
                            $seconds -= $hrs * 3600;
                            $mnts = floor($seconds / 60);
                            $seconds -= $mnts * 60;
                            $toReturn = "";
                            if ($days != 0) {
                                $toReturn = $toReturn . $days . " d ";
                            }
                            if ($hrs != 0) {
                                $toReturn = $toReturn . $hrs . " h ";
                            }
                            if ($mnts != 0) {
                                $toReturn = $toReturn . $mnts . " m ";
                            }
                            if ($seconds != 0) {
                                $toReturn = $toReturn . $seconds . " s";
                            }
                        }
                    } else {
                        $toReturn = "";
                    }
                    $csvParser->data[$key][$parameter2] = $toReturn;
                    if ($dataToSendBack[$toReturn] == null) {
                        $dataToSendBack[$toReturn] = 1;
                    } else {
                        $dataToSendBack[$toReturn] = $dataToSendBack[$toReturn] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function toTimestampNew($index, $parameter2){

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (in_array($index, $titles)) {
        $type = array_search($index, $titles);
        if ($types[$type]["type"] != "Date") {
            $answer = ["msg"=>"Selected column is not date type"];
            return $answer;
        }
    } else {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                $keyF = array_search($index, $csvParser->titles);
                $datetime = new DateTime();
                $types = unserialize($_SESSION["DATAPREP-types"]);
                $format = $types[$keyF]['format'];
                foreach ($csvParser->data as $key => $row) {
                    $newDate = $datetime->createFromFormat($format, $row[$index]);
                    if ($newDate) {
                        $seconds = date_timestamp_get($newDate);
                    } else {
                        $seconds = 0;
                    }
                    $csvParser->data[$key][$parameter2] = $seconds;
                    if ($dataToSendBack[$seconds] == null) {
                        $dataToSendBack[$seconds] = 1;
                    } else {
                        $dataToSendBack[$seconds] = $dataToSendBack[$seconds] + 1;
                    }
                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $countAll = 0;
    foreach ($dataToSendBack as $item => $z) {
        if (is_numeric($item)) {
            $countAll = $countAll + $item;
        }
    }

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e, "type" => ["type" => "Numeric", "count" => $countAll]];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Numeric", "count" => $countAll]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function exprNew($index, $parameter, $parameter2) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (in_array($parameter2, $titles)) {
        $answer = ["msg"=>"Column with this name already exist"];
        return $answer;
    }

    if (!$parameter || $parameter == "") {
        $answer = ["msg"=>"No expression selected"];
        return $answer;
    } else {
        if (strlen($parameter) > 250) {
            $answer = ["msg"=>"Expression can be only 250 characters long"];
            return $answer;
        }
    }

    $textToVal = $parameter;

    foreach ($titles as $k => $v) {
        if ($types[$k]["type"] == "Numeric") {
            $textToVal = str_replace("\"" . $v . "\"", "", $textToVal);
        }
    }

    $chars = ["ABS", "SIGN", "GCD", "LCM", "POW", "PRODUCT", "SQRT", "QUOTIENT", "MOD", "IF", "(", ")", "+", "-", "*", "/", "%", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ",", " ", "<=", ">=", "==", "<", ">"];

    foreach ($chars as $k2 => $v2) {
        $textToVal = str_replace($v2, "", $textToVal);
    }

    if ($textToVal != "") {
        $answer = ["msg"=>"Invalid characters: ".$textToVal];
        return $answer;
    }

    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);
    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    $textToVal = $parameter;

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $textToEval = $parameter;
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    foreach ($row as $key2 => $item) {
                        try {
                            if (is_numeric($item)) {
                                $number = ($item == (int) $item) ? (int) $item : (float) $item;
                            }
                        } catch (Exception $e) {
                            $number = 0;
                        }
                        $textToEval = str_replace("\"".$key2."\"", $number, $textToEval);
                    }
                    $textToEval2 = strtolower($textToEval);
                    $toSave = 0;
                    $result = false;
                    try {
                        $result = @eval('$toSave='.$textToEval2.";");
                    } catch (Exception $e) {
                        $toSave = 0;
                    }
                    if (!$result) {
                        $csvParser->data[$key][$parameter2] = 0;
                    } else {
                        $csvParser->data[$key][$parameter2] = $toSave;
                    }
                    if ($dataToSendBack[$toSave] == null) {
                        $dataToSendBack[$toSave] = 1;
                    } else {
                        $dataToSendBack[$toSave] = $dataToSendBack[$toSave] + 1;
                    }

                    $arrToPush = [];
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $countAll = 0;
    foreach ($dataToSendBack as $item => $z) {
        if (is_numeric($item)) {
            $countAll = $countAll + $item;
        }
    }

    $dataToSendBack = array_slice($dataToSendBack, 0, 300, true);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e, "type" => ["type" => "Numeric", "count" => $countAll]];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    array_push($types, ["type" => "Numeric", "count" => $countAll]);
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $answer;
}

function delete($what) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (!in_array($what, $titles)) {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    }

    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first == false) {
                $arrToPush = [];
                foreach ($csvParser->data as $key => $row) {
                    unset($csvParser->data[$key][$what]);
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($writing, $arrToPush);
                }
            }
            if ($first) {
                $key2 = array_search($what, $csvParser->titles);
                unset($csvParser->titles[$key2]);
                $firstHeads = $csvParser->titles;
                $first = false;
                fputcsv($writing, $firstHeads);
                $_SESSION["DATAPREP-titles"] = serialize($firstHeads);
            }
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $answer = ['deleted'=>'true'];
    $types = unserialize($_SESSION["DATAPREP-types"]);
    unset($types[(int)$what]);
    $newTypes = [];
    foreach ($types as $type => $x) {
        array_push($newTypes, $types[$type]);
    }
    $_SESSION["DATAPREP-types"] = serialize($newTypes);
    return $answer;
}

function changeType($what, $parameter, $parameter2) {

    $titles = unserialize($_SESSION["DATAPREP-titles"]);
    $types = unserialize($_SESSION["DATAPREP-types"]);
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);


    if ($titles == null || $types == null || $filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    if (!in_array($titles[$what], $titles)) {
        $answer = ["msg"=>"Selected column is not part of dataset"];
        return $answer;
    } else {
        $title = $titles[$what];
    }

    if ($parameter != "Date" && $parameter != "Text" && $parameter != "Numeric") {
        $answer = ["msg"=>"Selected type does not exist"];
        return $answer;
    }

    if (strlen($parameter2) > 250) {
        $answer = ["msg"=>"Replace parameter can be only 250 characters long"];
        return $answer;
    }

    if ($parameter == "Date") {
        $types[(int)$what] = ["type"=>$parameter, "format"=>$parameter2];
    } else if ($parameter == "Numeric") {
        $filePath = unserialize($_SESSION["DATAPREP-fileName"]);
        $myFile = fopen($filePath, "r");

        $unique = [];
        $firstHeads = [];
        $first = true;
        if ($myFile) {
            while (($line = fgets($myFile)) !== false) {
                $csvParser = new parseCSV();
                if (!$first) {
                    $csvParser->heading = false;
                    $csvParser->fields = $firstHeads;
                }
                $csvParser->delimiter = ",";
                $csvParser->parse($line);
                if ($first == false) {
                    foreach ($csvParser->data as $row) {
                        if ($unique[$row[$title]] == null) {
                            $unique[$row[$title]] = 1;
                        } else {
                            $unique[$row[$title]] = $unique[$row[$title]] + 1;
                        }
                    }
                }
                if ($first) {
                    $firstHeads = $csvParser->titles;
                    $first = false;
                }
            }

            fclose($myFile);
            $countAll = 0;
            foreach ($unique as $item => $z) {
                if (is_numeric($item)) {
                    $countAll = $countAll + $item;
                }
            }
            $types[(int)$what] = ["type"=>$parameter, "count"=>$countAll];
        }
    } else {
        $types[(int)$what] = ["type"=>$parameter];
    }
    $_SESSION["DATAPREP-types"] = serialize($types);
    return $types[(int)$what];
}

function sign($x) {
    $toReturn = 0;
    if ($x === 0) {
        $toReturn = 0;
    } else if ($x < 0) {
        $toReturn = -1;
    } else if ($x > 0) {
        $toReturn = 1;
    }

    return $toReturn;
}

function gcd($a, $b) {
    return (!$b)?$a:gcd($b,$a%$b);
}

function lcm($a, $b) {
    return ($a * $b) / gcd($a, $b);
}

function product($x, $y) {
    return $x * $y;
}

function quotient($x, $y) {
    return $x / $y;
}

function mod($x, $y) {
    return $x % $y;
}

function myif($x, $y, $z) {
    if ($x) {
        return $y;
    } else {
        return $z;
    }
}