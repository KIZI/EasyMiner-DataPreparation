<?php
ini_set('memory_limit', '2048M');
date_default_timezone_set('Europe/Prague');
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
        echo json_encode(convertToPercentageNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"], $_POST["parameter3"]));

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

    } elseif ($_POST["action"] == "expressionNew") {

        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(exprNew($_POST["column"], $_POST["parameter"], $_POST["parameter2"]));

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

function convertToPercentageNew($index, $parameter, $parameter2, $parameter3) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    $count = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        $count = count($csvParser->titles) -1;
        array_push($csvParser->titles, $parameter2);
        foreach ($csvParser->data as $key => $row) {
            $changedValue = round(($row[$index] / $parameter) * 100, (int)$parameter3)."";
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
    array_push($types, ["type" => "Text"]);
    $_SESSION["types"] = serialize($types);
    return $answer;
}

function exprNew($index, $parameter, $parameter2) {
    $fileNumber = unserialize($_SESSION["fileNumber"]);
    $dataToSendBack = [];
    $i = 0;
    $count = 0;

    $textToVal = $parameter;
    $parserForVal = unserialize($_SESSION["data0"]);
    $titlesForVal = $parserForVal;

    foreach ($parserForVal->titles as $k => $v) {
        $textToVal = str_replace($v, "", $textToVal);
    }

    $chars = ["ABS", "SIGN", "GCD", "LCM", "POWER", "PRODUCT", "SQRT", "QUOTIENT", "MOD", "(", ")", "+", "-", "*", "/", "%", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ",", " "];

    foreach ($chars as $k2 => $v2) {
        $textToVal = str_replace($v2, "", $textToVal);
    }

    if ($textToVal == "") {
        while ($i <= $fileNumber) {
            $csvParser = unserialize($_SESSION["data".$i]);
            $count = count($csvParser->titles) -1;
            array_push($csvParser->titles, $parameter2);
            foreach ($csvParser->data as $key => $row) {
                $textToEval = $parameter;
                foreach ($row as $key2 => $item) {
                    $textToEval = str_replace($key2, $item, $textToEval);
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
            $_SESSION["data".$i] = serialize($csvParser);
            $i++;
        }
        $e = ['title'=>$parameter2, 'id'=>$count];
        $answer = ['row'=>$dataToSendBack, 'title'=>$e];
        $types = unserialize($_SESSION["types"]);
        array_push($types, ["type" => "Text"]);
        $_SESSION["types"] = serialize($types);
        return $answer;
    } else {
        return ["Invalid chars"=>$textToVal];
    }
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
    return (!$b)?$a:GCD($b,$a%$b);
}

function lcm($a, $b) {
    return ($a * $b) / GCD($a, $b);
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