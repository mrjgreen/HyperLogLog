<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/randomGenerator.php';

$set1 = randomSet(90000);

$set2 = randomSet(70000);

$set3 = randomSet(80000);

echo "Number of words in set 1: " . count($set1) . "\n";

echo "Number of words in set 2: " . count($set2) . "\n";

echo "Number of words in set 3: " . count($set3) . "\n";

$intersection = array_intersect($set1, $set2, $set3);

$union = array_merge($set1, $set2, $set3);

$intersectionCount = cardinality($intersection);

echo "Cardinailiy of union: " . cardinality($union) . "\n";

echo "Number of words in intersection: " . $intersectionCount . "\n";

echo "------\nLogLog\n";

$log_logs = array();

foreach(array($set1, $set2, $set3) as $i => $set)
{
    $log_log = new HyperLogLog\MinHash();

    foreach ($set as $word) {
        $log_log->add($word);
    }

    $log_logs[] = $log_log;

    echo "Added set " . ($i + 1) . "\n";
}

$merge_log = array_pop($log_logs);

$new_log_log = new HyperLogLog\MinHash();

$new_log_log->import($merge_log->export());

echo $merge_log->count() . "\n";

echo $new_log_log->count() . "\n";

$log_logs[] = $new_log_log;

$count = \HyperLogLog\Utils\MinHashIntersector::count($log_logs);

echo "Intersection complete\n";

echo $count . "\n" . 'error: ' . number_format(($count - $intersectionCount) / ($intersectionCount / 100.0), 3) . '%' . PHP_EOL;

foreach($log_logs as $log)
{
    $export = $log->export();
    echo "Size of export: " . strlen($export[0]) . ', ' . strlen($export[1]) . "\n";
}