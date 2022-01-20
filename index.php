#!/usr/local/bin/php
<?php

ini_set("memory_limit",'-1');
ini_set('max_execution_time', 0);
ini_set('auto_detect_line_endings', true);

require 'vendor/autoload.php';
use Rap2hpoutre\FastExcel\FastExcel;

$filePath = $argv[1];;
$collections = (new FastExcel)->import($filePath);
$domains = [];

foreach ($collections as $collection) {

    $arrayValue = array_values($collection);
    
    $phone = $arrayValue[0];
    $domain = $arrayValue[1];

    if (isset($domains[$domain])) {
        $domains[$domain]++;
    } else {
        $domains[$domain] = 1;
    }
}

$exportFileName = 'result-' . $filePath;

$fp = fopen($exportFileName, 'w');
foreach ($domains as $key => $fields) {
    fputcsv($fp, [
        $key,
        $fields
    ]);
}
fclose($fp);

dd("Completed : $exportFileName");