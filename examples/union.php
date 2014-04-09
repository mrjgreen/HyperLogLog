<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/randomGenerator.php';

$set1 = randomSet();

$set2 = randomSet();

echo "Number of words in set 1: " . count($set1) . "\n";

echo "Number of words in set 2: " . count($set2) . "\n";

$union = array_merge($set1, $set2);

echo "Number of words in union: " . count($union) . "\n";

echo "------\nCardinality of union\n";

echo $card = cardinality($union) . "\n";

echo "------\nLogLog\n";

$log_log1 = new HyperLogLog\Basic(14);

foreach ($set1 as $word) {
    $log_log1->add($word);
}
echo "Added set 1\n";

$log_log2 = new HyperLogLog\Basic(14);

foreach ($set2 as $word) {
    $log_log2->add($word);
}
echo "Added set 2\n";

$log_log1->union($log_log2);

echo "Union complete\n";

$count = $log_log1->count() . "\n";

echo $count . 'error: ' . number_format(($count - $card) / ($card / 100.0), 3) . '%' . PHP_EOL;
