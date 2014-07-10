<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/randomGenerator.php';

// adjusted so the union is almost the size of the hash k
$set1 = randomSet(110000);

$set2 = randomSet(0);

$set3 = randomSet(120000);

echo "Number of words in set 1: " . count($set1) . "\n";

echo "Number of words in set 2: " . count($set2) . "\n";

echo "Number of words in set 3: " . count($set3) . "\n";


echo "------\n";

echo "Cardinailiy of set 1: " . cardinality($set1) . "\n";

echo "Cardinailiy of set 2: " . cardinality($set2) . "\n";

echo "Cardinailiy of set 3: " . cardinality($set3) . "\n";

$intersection = array_intersect($set1, $set2, $set3);

$union = array_merge($set1, $set2, $set3);

$intersectionCount = cardinality($intersection);

echo "Cardinailiy of union: " . cardinality($union) . "\n";

echo "Cardinailiy of intersection: " . $intersectionCount . "\n";

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

$count = \HyperLogLog\Utils\MinHashIntersector::count($log_logs);

echo "intersection complete: count: $count\n";

if($count)
{
    echo "Error: 100% - count should be zero\n";
}

echo "Error: 0% - count is zero\n";