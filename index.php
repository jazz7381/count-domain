#!/usr/local/bin/php
<?php

ini_set("memory_limit",'-1');
ini_set('max_execution_time', 0);
ini_set('auto_detect_line_endings', true);

require 'vendor/autoload.php';
use Rap2hpoutre\FastExcel\FastExcel;

$filePath = $argv[1];

$domains = [];

exec("/bin/bash " . __DIR__ . '/' . 'split.sh' . " splits/{$filePath} 100000");

foreach (getSplitFiles($filePath) as $splitFile) {
    $collections = (new FastExcel)->import('splits/' . $splitFile);
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

    dump($splitFile);
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


function getSplitFiles($filename)
{
    $files = [];

    foreach (scandir('splits') as $file) {

        if ($file == $filename || $file == '.' || $file == '..') {
            continue;
        }

        $files[] = $file;
    }

    return $files;
}

