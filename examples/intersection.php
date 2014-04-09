<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/randomGenerator.php';

$set1 = randomSet(1000);

$set2 = randomSet(1000);

$set3 = randomSet(1000);

echo "Number of words in set 1: " . count($set1) . "\n";

echo "Number of words in set 2: " . count($set2) . "\n";

echo "Number of words in set 3: " . count($set3) . "\n";

$intersection = array_intersect($set1, $set2, $set3);

$union = array_merge($set1, $set2, $set3);

$intersectionCount = count($intersection);

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

list($minHashIntersection, $minHashK, $hllUnion) = \HyperLogLog\Utils\MinHashIntersector::jaccard($log_logs);

$hllUnionCount = $hllUnion->count();

echo "Hll union: " . $hllUnionCount . "\n";

echo "Min hash intersection: " . $minHashIntersection . "\n";

echo "Min hash k: " . $minHashK . "\n";

$count = ($minHashIntersection / $minHashK) * $hllUnionCount;

echo "intersection complete\n";

echo $count . "\n" . 'error: ' . number_format(($count - $intersectionCount) / ($intersectionCount / 100.0), 3) . '%' . PHP_EOL;
