<?php
ini_set('memory_limit', '256M');
ini_set('upload_max_filesize', '150M');
ini_set('post_max_size', '151M');
session_start();
require_once('../lib/parsecsv.lib.php');
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    if($_FILES['csv']['error'] == 0 && in_array($_FILES['csv']['type'],$mimes)) {
        try {

            $uniqName = uniqid('csv_', TRUE);
            $tmpFile = "/tmp/$uniqName.csv";
            move_uploaded_file($_FILES['csv']['tmp_name'], $tmpFile);
            $_SESSION["DATAPREP-fileName"] = serialize($tmpFile);
            $types = null;
            $_SESSION["DATAPREP-types"] = serialize($types);


            $reading1 = fopen($tmpFile, 'r');
            $writing1 = fopen($tmpFile.'.tmp', 'w');

            if ($reading1) {
                $linesInLines = false;
                $stillLinesInLines = false;
                $text = "";
                while (($line = fgets($reading1)) !== false) {
                    $line = mb_convert_encoding($line, 'UTF-8', $_POST["encoding"]);
                    $quotes = substr_count($line,"\"");
                    if ($quotes != 0) {
                        if ($quotes % 2 != 0) {
                            if ($linesInLines == true) {
                                $stillLinesInLines = false;
                                $linesInLines = false;

                            } else {
                                $linesInLines = true;

                            }
                        } else {
                            if ($linesInLines && $stillLinesInLines) {
                                $linesInLines = false;

                            }
                        }
                    } else if ($quotes == 0 && $linesInLines) {
                        $stillLinesInLines = true;

                    } else if ($quotes == 0 && !$linesInLines) {
                        $text = $line;
                    }
                    if (!$linesInLines) {
                        file_put_contents($tmpFile.'.tmp', $line, FILE_APPEND);
                    } else {
                        $line = preg_replace('/\s\s+/', ' ', $line);
                        file_put_contents($tmpFile.'.tmp', $line, FILE_APPEND);
                    }
                }
            }

            fclose($reading1);
            fclose($writing1);
            rename($tmpFile.'.tmp', $tmpFile);

            $reading = fopen($tmpFile, 'r');
            $writing = fopen($tmpFile.'.tmp', 'w');

            $first = true;
            $firstHeads = [];
            $i = 0;

            $dataTrain = [];

            if ($reading) {
                while (($line = fgets($reading)) !== false) {
                    $csvParser = new parseCSV();
                    if (!$first) {
                        $csvParser->heading = false;
                        $csvParser->fields = $firstHeads;
                    }
                    $csvParser->delimiter = $_POST["separator"];
                    $csvParser->parse($line);
                    if ($first == false) {
                        if ($i < 6) {
                            if ($csvParser->data !== []) {
                                array_push($dataTrain, $csvParser->data);
                            }
                            $i++;
                        }
                        foreach ($csvParser->data as $key => $row) {
                            $arrToPush = [];
                            $first1 = true;
                            foreach ($csvParser->data[$key] as $val) {
                                array_push($arrToPush, $val);
                                if ($first1) {
                                    $data = "\"".$val."\"";
                                    $first1 = false;
                                } else {
                                    $data = $data.","."\"".$val."\"";
                                }
                            }
                            file_put_contents($tmpFile.'.tmp', $data."\n", FILE_APPEND);
                            //fputcsv($writing, $arrToPush, ",", "\n");
                        }
                    }
                    if ($first) {
                        $firstHeads = $csvParser->titles;
                        $first = false;
                        $count = count($csvParser->titles) -1;
                        $first1 = true;
                        $titles = "";
                        foreach ($csvParser->titles as $val) {
                            if ($first1) {
                                $titles = "\"".$val."\"";
                                $first1 = false;
                            } else {
                                $titles = $titles.","."\"".$val."\"";
                            }
                        }
                        //fputcsv($writing, $firstHeads, ",", "\n");
                        file_put_contents($tmpFile.'.tmp', $titles."\n", FILE_APPEND);
                        $_SESSION["DATAPREP-titles"] = serialize($csvParser->titles);
                    }
                }
            }
            fclose($reading);
            fclose($writing);
            rename($tmpFile.'.tmp', $tmpFile);

            header('Content-Type: application/json');
            echo json_encode(["data"=>$dataTrain]);
            http_response_code(200);
        } catch (Exception $e) {
            http_response_code(500);
        }
    } else {
        http_response_code(404);
    }

}