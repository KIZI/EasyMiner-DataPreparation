<?php
date_default_timezone_set('Europe/Prague');
ini_set('memory_limit', '256M');
session_start();

foreach (glob("tmp/*.csv") as $file) {
    if(time() - filectime($file) > 86400){
        unlink($file);
    }
}

require_once('../lib/parsecsv.lib.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    header('Content-Type: application/json');
    echo json_encode(dataToSend());
    http_response_code(200);
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);
    $myFile = fopen($filePath, "r");
    $output = fopen('php://output', 'w');

    $first = true;
    $firstHeads = [];

    if ($myFile) {
        while (($line = fgets($myFile)) !== false) {
            $csvParser = new parseCSV();
            if (!$first) {
                $csvParser->heading = false;
                $csvParser->fields = $firstHeads;
            }
            $csvParser->delimiter = ",";
            $csvParser->parse($line);
            if ($first) {
                $firstHeads = $csvParser->titles;
                $first = false;
                fputcsv($output, $firstHeads);
            }
            if ($first == false) {
                $arrToPush = [];
                foreach ($csvParser->data as $key => $row) {
                    foreach ($csvParser->data[$key] as $val) {
                        array_push($arrToPush, $val);
                    }
                    fputcsv($output, $arrToPush);
                }
            }
        }
    }
} else {
    http_response_code(405);
}

function dataToSend() {
    $filePath = unserialize($_SESSION["DATAPREP-fileName"]);

    if ($filePath == null) {
        $answer = ["msg"=>"No data provided"];
        return $answer;
    }

    $myFile = fopen($filePath, "r");
    $types = unserialize($_SESSION["DATAPREP-types"]);

    $answerTitles = [];
    $simpleTitles = [];

    $unique = [];
    $count = 0;
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
                    foreach ($row as $key2 => $item) {
                        $k = array_search($key2, $simpleTitles);
                        if ($unique[$k][$item . ""] == null) {
                            if (count($unique[$k]) <= 300 || is_numeric($item)) {
                                $unique[$k][$item . ""] = 1;
                            }
                        } else {
                            $unique[$k][$item . ""] = $unique[$k][$item . ""] + 1;
                        }
                    }
                }
                $count++;
            }
            if ($first) {
                $firstHeads = $csvParser->titles;
                $first = false;
                $simpleTitles = $csvParser->titles;

                $i = 0;
                foreach ($csvParser->titles as $value) {
                    $e = ['id' => $i, 'title' => $value];
                    array_push($answerTitles, $e);
                    $i++;
                }
            }
        }

        fclose($myFile);
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
                array_push($types, ["type" => "Numeric", "count" => 0]);
            } elseif ($isDate > 7 || sizeof($unique[$v]) - 2 < $isDate) {
                array_push($types, ["type" => "Date", "format" => $format]);
            } else {
                array_push($types, ["type" => "Text"]);
            }

            $countAll = 0;
            foreach ($unique[$v] as $item => $z) {
                if ($types[$v]["type"] == "Numeric") {
                    if (is_numeric($item)) {
                        $countAll = $countAll + $item;
                    }
                }
            }
            if ($types[$v]["type"] == "Numeric") {
                $types[$v]["count"] = $countAll;
            }

        }
        $_SESSION["DATAPREP-types"] = serialize($types);
    }

    foreach ($unique as $v => $x) {
        $unique[$v] = array_slice($unique[$v], 0, 300, true);
    }

    $toReturn = ["titles"=>$answerTitles, "rows"=>$unique, "count"=>$count, "types"=>$types];
    return $toReturn;
}