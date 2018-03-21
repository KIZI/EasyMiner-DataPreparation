<?php
ini_set('memory_limit', '2048M');
session_start();
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

    } elseif ($_POST["action"] == "remove") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(remove($_POST["column"], $_POST["parameter"]));

    } elseif ($_POST["action"] == "removeNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(removeNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    } elseif ($_POST["action"] == "regEx") {

        header('Content-Type: application/json');
        echo json_encode(regEx($_POST["column"], $_POST["parameter"]));
        http_response_code(200);

    } elseif ($_POST["action"] == "regExNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(regExNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    } elseif ($_POST["action"] == "toDaysNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(toDaysNew($_POST["column"], $_POST["parameter"]));

    } elseif($_POST["action"] == "delete") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(delete($_POST["column"]));

    } elseif($_POST["action"] == "changeType") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(changeType($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

    }else {
        http_response_code(404);
    }

} else {
    http_response_code(405);
}

function convertToPercentage($index, $parameter) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        foreach ($csvParser->data as $key => $row) {
            $changedValue = round(($row[$index] / $parameter) * 100, 4)."";
            $csvParser->data[$key][$index] = $changedValue;
            if ($dataToSendBack[$changedValue] == null) {
                $dataToSendBack[$changedValue] = 1;
            } else {
                $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
            }
        }
        $_SESSION["data".$i] = serialize($csvParser);
        $i++;
    }
    return $dataToSendBack;
}

function convertToPercentageNew($index, $parameter, $parameter2) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    $count = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        $count = count($csvParser->titles) -1;
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
        $_SESSION["data".$i] = serialize($csvParser);
        $i++;
    }
    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Numeric"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function addColumn($index, $parameter) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data" . $i]);
        foreach ($csvParser->data as $key => $row) {
            $changedValue = ($row[$index] + $row[$parameter]);
            $csvParser->data[$key][$index] = $changedValue;
            if ($dataToSendBack[$changedValue] == null) {
                $dataToSendBack[$changedValue] = 1;
            } else {
                $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
            }
        }
        $_SESSION["data" . $i] = serialize($csvParser);
        $i++;
    }
    return $dataToSendBack;
}

function addColumnNew($index, $parameter, $parameter2) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    $count = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        $count = count($csvParser->titles) -1;
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
        $_SESSION["data".$i] = serialize($csvParser);
        $i++;
    }
    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Numeric"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function subtractColumn($index, $parameter) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data" . $i]);
        foreach ($csvParser->data as $key => $row) {
            $changedValue = ($row[$index] - $row[$parameter]);
            $csvParser->data[$key][$index] = $changedValue;
            if ($dataToSendBack[$changedValue] == null) {
                $dataToSendBack[$changedValue] = 1;
            } else {
                $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
            }
        }
        $_SESSION["data" . $i] = serialize($csvParser);
        $i++;
    }
    return $dataToSendBack;
}

function subtractColumnNew($index, $parameter, $parameter2) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    $count = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        $count = count($csvParser->titles) -1;
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
        $_SESSION["data".$i] = serialize($csvParser);
        $i++;
    }
    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Numeric"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function remove($index, $parameter) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data" . $i]);
        foreach ($csvParser->data as $key => $row) {
            $changedValue = str_replace($parameter, "", $row[$index]);
            $csvParser->data[$key][$index] = $changedValue;
            if ($dataToSendBack[$changedValue] == null) {
                $dataToSendBack[$changedValue] = 1;
            } else {
                $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
            }
        }
        $_SESSION["data" . $i] = serialize($csvParser);
        $i++;
    }
    return $dataToSendBack;
}

function removeNew($index, $parameter, $parameter2) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    $count = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        $count = count($csvParser->titles) -1;
        array_push($csvParser->titles, $parameter2);
        foreach ($csvParser->data as $key => $row) {
            $changedValue = str_replace($parameter, "", $row[$index]);
            $csvParser->data[$key][$parameter2] = $changedValue;
            if ($dataToSendBack[$changedValue] == null) {
                $dataToSendBack[$changedValue] = 1;
            } else {
                $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
            }
        }
        $_SESSION["data".$i] = serialize($csvParser);
        $i++;
    }
    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function regEx($index, $parameter) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data" . $i]);
        foreach ($csvParser->data as $key => $row) {
            preg_match($parameter, $row[$index], $matches);
            $changedValue = $matches[0];
            $csvParser->data[$key][$index] = $changedValue;
            if ($dataToSendBack[$changedValue] == null) {
                $dataToSendBack[$changedValue] = 1;
            } else {
                $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
            }
        }
        $_SESSION["data" . $i] = serialize($csvParser);
        $i++;
    }
    return $dataToSendBack;
}

function regExNew($index, $parameter, $parameter2) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    $count = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        $count = count($csvParser->titles) -1;
        array_push($csvParser->titles, $parameter2);
        foreach ($csvParser->data as $key => $row) {
            preg_match($parameter, $row[$index], $matches);
            $changedValue = $matches[0];
            $csvParser->data[$key][$parameter2] = $changedValue;
            if ($dataToSendBack[$changedValue] == null) {
                $dataToSendBack[$changedValue] = 1;
            } else {
                $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
            }
        }
        $_SESSION["data".$i] = serialize($csvParser);
        $i++;
    }
    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    array_push($types, ["type" => "Text"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function toDaysNew($index, $parameter2) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    $count = 0;
    $keyF = "";
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        $count = count($csvParser->titles) -1;
        array_push($csvParser->titles, $parameter2);
        $keyF = array_search($index, $csvParser->titles);
        $datetime = new DateTime();
        $types = unserialize($_SESSION["types"]);
        $format = $types[$keyF]['format'];
        foreach ($csvParser->data as $key => $row) {
            $newDate = $datetime->createFromFormat($format, $row[$index]);
            $changedValue = date_format($newDate, 'l');
            $csvParser->data[$key][$parameter2] = $changedValue;
            if ($dataToSendBack[$changedValue] == null) {
                $dataToSendBack[$changedValue] = 1;
            } else {
                $dataToSendBack[$changedValue] = $dataToSendBack[$changedValue] + 1;
            }
        }
        $_SESSION["data".$i] = serialize($csvParser);
        $i++;
    }
    $e = ['title'=>$parameter2, 'id'=>$count];
    $answer = ['row'=>$dataToSendBack, 'title'=>$e];
    $types = unserialize($_SESSION["types"]);
    $format = $types[$keyF]['format'];
    array_push($types, ["type" => "Date", "format" => $format]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function delete($what) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $i = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data" . $i]);
        $key2 = array_search($what, $csvParser->titles);
        unset($csvParser->titles[$key2]);
        foreach ($csvParser->data as $key => $row) {
            unset($csvParser->data[$key][$what]);
        }
        $_SESSION["data" . $i] = serialize($csvParser);
        $i++;
    }
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