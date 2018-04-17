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
    $filePath = unserialize($_SESSION["fileName"]);
    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];
    $echo = true;

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    $changedValue = round(($row[$index] / $parameter) * 100, (int)$parameter3)."";
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Numeric"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function addColumnNew($index, $parameter, $parameter2) {

    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    $changedValue = ($row[$index] + $row[$parameter]);
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Numeric"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function subtractColumnNew($index, $parameter, $parameter2) {

    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    $changedValue = ($row[$index] - $row[$parameter]);
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Numeric"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function removeNew($index, $parameter, $parameter2, $parameter3) {

    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Numeric"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function regExNew($index, $parameter, $parameter2) {

    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function regExRepNew($index, $parameter, $parameter2, $parameter3) {

    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function joinTextNew($index, $parameter, $parameter2, $parameter3) {

    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function toDaysNew($index, $parameter2) {

    $filePath = unserialize($_SESSION["fileName"]);
    $dataToSendBack = [];
    $count = 0;
    $reading = fopen($filePath, 'r');
    $writing = fopen($filePath.'.tmp', 'w');

    $first = true;
    $firstHeads = [];
    $keyF = "";

    if ($reading) {
        while (($line = fgets($reading)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
            if ($first == false) {
                $keyF = array_search($index, $csvParser->titles);
                $datetime = new DateTime();
                $types = unserialize($_SESSION["types"]);
                $format = $types[$keyF]['format'];
                foreach ($csvParser->data as $key => $row) {
                    $newDate = $datetime->createFromFormat($format, $row[$index]);
                    if ($newDate) {
                        $changedValue = date_format($newDate, 'l');
                    } else {
                        $changedValue = "Invalit date";
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    $format = $types[$keyF]['format'];
    array_push($types, ["type" => "Text"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function divDayNew($index, $index2, $parameter2) {

    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
            if ($first == false) {
                $keyF = array_search($index, $csvParser->titles);
                $keyF2 = array_search($index2, $csvParser->titles);
                $datetime = new DateTime();
                $types = unserialize($_SESSION["types"]);
                $format = $types[$keyF]['format'];
                $format2 = $types[$keyF2]['format'];
                foreach ($csvParser->data as $key => $row) {
                    $newDate = $datetime->createFromFormat($format, $row[$index]);
                    $newDate2 = $datetime->createFromFormat($format2, $row[$index2]);
                    $seconds = date_timestamp_get($newDate) - date_timestamp_get($newDate2);
                    $days = floor($seconds / (3600*24));
                    $seconds  -= $days * 3600 * 24;
                    $hrs   = floor($seconds / 3600);
                    $seconds  -= $hrs * 3600;
                    $mnts = floor($seconds / 60);
                    $seconds  -= $mnts * 60;
                    $toReturn = "";
                    if ($days != 0) {
                        $toReturn = $toReturn.$days." d ";
                    }
                    if ($hrs != 0) {
                        $toReturn = $toReturn.$hrs." h ";
                    }
                    if ($mnts != 0) {
                        $toReturn = $toReturn.$mnts." m ";
                    }
                    if ($seconds != 0) {
                        $toReturn = $toReturn.$seconds." s";
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function toTimestampNew($index, $parameter2){
    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                fputcsv($writing, $firstHeads);
            }
            if ($first == false) {
                $keyF = array_search($index, $csvParser->titles);
                $datetime = new DateTime();
                $types = unserialize($_SESSION["types"]);
                $format = $types[$keyF]['format'];
                foreach ($csvParser->data as $key => $row) {
                    $newDate = $datetime->createFromFormat($format, $row[$index]);
                    $seconds = date_timestamp_get($newDate);
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Numeric"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function exprNew($index, $parameter, $parameter2) {

    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $firstHeads = $csvParser->titles;
                array_push($firstHeads, $parameter2);
                $first = false;
                $count = count($csvParser->titles) -1;
                foreach ($csvParser->titles as $k => $v) {
                    $textToVal = str_replace("\"".$v."\"", "", $textToVal);
                }

                $chars = ["ABS", "SIGN", "GCD", "LCM", "POWER", "PRODUCT", "SQRT", "QUOTIENT", "MOD", "IF", "(", ")", "+", "-", "*", "/", "%", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ",", " ", "=", "<", ">"];

                foreach ($chars as $k2 => $v2) {
                    $textToVal = str_replace($v2, "", $textToVal);
                }

                $textToEval = str_replace("IF", "MYIF", $textToEval);
                if ($textToEval[0] == "=") {
                    $textToEval = substr($textToEval, 1);
                }

                if ($textToVal != "") {
                    return ["Invalid chars"=>$textToVal];
                }
                fputcsv($writing, $firstHeads);
            }
            if ($first == false) {
                foreach ($csvParser->data as $key => $row) {
                    foreach ($row as $key2 => $item) {
                        $number = ($item == (int) $item) ? (int) $item : (float) $item;
                        $textToEval = str_replace("\"".$key2."\"", $number, $textToEval);
                    }
                    $textToEval2 = strtolower($textToEval);
                    $toSave = null;
                    eval('$toSave='.$textToEval2.";");
                    $csvParser->data[$key][$parameter2] = $toSave;
                    if ($dataToSendBack[$toSave] == null) {
                        $dataToSendBack[$toSave] = 1;
                    } else {
                        $dataToSendBack[$toSave] = $dataToSendBack[$toSave] + 1;
                    }
                }
                foreach ($csvParser->data as $key => $row) {
                    foreach ($row as $key2 => $item) {
                        $number = 0;
                        try {
                            $number = ($item == (int) $item) ? (int) $item : (float) $item;
                        } catch (Exception $e) {
                            $number = 0;
                        }
                        $textToEval = str_replace("\"".$key2."\"", $number, $textToEval);
                    }
                    $textToEval2 = strtolower($textToEval);
                    $toSave = 0;
                    try {
                        eval('$toSave='.$textToEval2.";");
                    } catch (Exception $e) {
                        $toSave = 0;
                    }
                    $csvParser->data[$key][$parameter2] = $toSave;
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function delete($what) {

    $filePath = unserialize($_SESSION["fileName"]);
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
            if ($first) {
                $key2 = array_search($what, $csvParser->titles);
                unset($csvParser->titles[$key2]);
                $firstHeads = $csvParser->titles;
                $first = false;
                fputcsv($writing, $firstHeads);
            }
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
        }
    }
    fclose($reading); fclose($writing);
    rename($filePath.'.tmp', $filePath);

    $answer = ['deleted'=>'true'];
    $types = unserialize($_SESSION["types"]);
    unset($types[(int)$what]);
    $newTypes = [];
    foreach ($types as $type => $x) {
        array_push($newTypes, $types[$type]);
    }
    $_SESSION["types"] = serialize($newTypes);
    return $answer;
}

function changeType($what, $parameter, $parameter2) {
    $types = unserialize($_SESSION["types"]);
    if ($parameter == "Date") {
        $types[(int)$what] = ["type"=>$parameter, "format"=>$parameter2];
    } else {
        $types[(int)$what] = ["type"=>$parameter];
    }
    $_SESSION["types"] = serialize($types);
    return ["changed"=>"ok"];
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

function gdc($a, $b) {
    return (!$b)?$a:gdc($b,$a%$b);
}

function lcm($a, $b) {
    return ($a * $b) / gdc($a, $b);
}

function power($x, $y) {
    return pow($x, $y);
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