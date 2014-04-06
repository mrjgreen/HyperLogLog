<?php

include __DIR__ . '/HyperLogLog.php';

function generateWords($count) {
    $result = array();

    while ($count > 0) {
        $count--;
        $result[] = rand(10000,500000);
    }

    return $result;
}

function cardinality($arr) {
    $arr = array_count_values($arr);
    return count($arr);
}

$words = generateWords(200000);

echo "Number of words\n" . count($words) . "\n";

echo "------\nCardinality\n";

echo $card = cardinality($words) . "\n";

echo "------\nLogLog\n";

$log_log = new HyperLogLog();

foreach ($words as $word) {
    $log_log->add($word);
}

$count = $log_log->count() . "\n";

echo $count . 'error: ' . round(($count - $card) / ($card / 100.0)) . '%' . PHP_EOL;


// Import this data set into another hyperlog log
$str = $log_log->export();

$log_log2 = new HyperLogLog();

// ... $log_log2->add('otherset');

$log_log2->merge($str);

var_dump($log_log2->count());
