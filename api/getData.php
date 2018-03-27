<?php
date_default_timezone_set('Europe/Prague');
ini_set('memory_limit', '2048M');
session_start();
require_once('../lib/parsecsv.lib.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(dataToSend());
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');

    $output = fopen('php://output', 'w');

    $fileNumber = unserialize($_SESSION["fileNumber"]);

    $csvParser = unserialize($_SESSION["data0"]);

    fputcsv($output, $csvParser->titles);

    $i = 0;
    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        foreach ($csvParser->data as $row) {
            $arrToPush = [];
            foreach ($row as $val) {
                array_push($arrToPush, $val);
            }
            fputcsv($output, $arrToPush);
        }
        $i++;
    }
} else {
    http_response_code(405);
}

function dataToSend() {
    $csvParser = unserialize($_SESSION["data0"]);
    $types = unserialize($_SESSION["types"]);

    $answerTitles = [];
    $i = 0;
    $simpleTitles = $csvParser->titles;

    foreach ($csvParser->titles as $value) {
        $e = ['id' => $i, 'title' => $value];
        array_push($answerTitles, $e);
        $i++;
    }

    $unique = [];
    $count = 0;

    $fileNumber = unserialize($_SESSION["fileNumber"]);

    $i = 0;

    while ($i <= $fileNumber) {
        $csvParser = unserialize($_SESSION["data".$i]);
        foreach ($csvParser->data as $row) {
            foreach ($row as $key2 => $item) {
                $k = array_search($key2, $simpleTitles);
                if ($unique[$k][$item . ""] == null) {
                    $unique[$k][$item . ""] = 1;

                } else {
                    $unique[$k][$item . ""] = $unique[$k][$item . ""] + 1;
                }
            }
        }
        $i++;
    }

    if ($types == null) {
        $types = [];
        foreach ($unique as $v => $x) {
            $m = 0;
            $isNumber = 0;
            $isDate = 0;
            $format = "";
            foreach ($unique[$v] as $item => $z) {
                if ($m < 10) {
                    $m++;
                    if (is_numeric($item)) {
                        $isNumber++;
                    } elseif ((bool)strtotime($item)) {
                        $isDate++;
                        if ($isDate == 1) {
                            $array = explode("/", $item);
                            if ($array[1]) {
                                if (intval($array[0]) > 35) {
                                    $format = "Y/m/d";
                                } elseif (intval($array[2]) > 35) {
                                    $format = "d/m/Y";
                                }
                            }
                            $array = explode("-", $item);
                            if ($array[1]) {
                                if (intval($array[0]) > 35) {
                                    $format = "Y-m-d";
                                } elseif (intval($array[2]) > 35) {
                                    $format = "d-m-Y";
                                }
                            }
                            $array = explode(".", $item);
                            if ($array[1]) {
                                if (intval($array[0]) > 35) {
                                    $format = "Y.m.d";
                                } elseif (intval($array[2]) > 35) {
                                    $format = "d.m.Y";
                                }
                            }
                        }
                    }
                } else {
                    break;
                }
            }

            if($isNumber > 7 || sizeof($unique[$v]) - 2 < $isNumber) {
                array_push($types, ["type" => "Numeric"]);
            } elseif ($isDate > 7 || sizeof($unique[$v]) - 2 < $isDate) {
                array_push($types, ["type" => "Date", "format" => $format]);
            } else {
                array_push($types, ["type" => "Text"]);
            }
        }
        $_SESSION["types"] = serialize($types);
    }

    $toReturn = ["titles"=>$answerTitles, "rows"=>$unique, "count"=>$count, "types"=>$types];
    return $toReturn;
}