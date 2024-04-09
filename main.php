<?php

require 'includes/functions.php';

$arr = parseCSV("data/google_dataset.csv", ",", 100000, true);
//$arr = parseCSV("data/facebook_dataset.csv", ",", 15, true);
//$arr = parseCSV("data/website_dataset.csv", ";", 15, true);

print_r(count($arr));

foreach($arr as $val)
$test[$val['name']] = $val['name'];

//print_r($test);
echo "\n\n\n" . count($test);