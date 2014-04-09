<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/randomGenerator.php';

$words = randomSet();

echo "Number of words\n" . count($words) . "\n";

echo "------\nCardinality\n";

echo $card = cardinality($words) . "\n";

echo "------\nLogLog\n";

$log_log = new HyperLogLog\Basic();

foreach ($words as $word) {
    $log_log->add($word);
}

$count = $log_log->count() . "\n";

echo $count . 'error: ' . number_format(($count - $card) / ($card / 100.0), 3) . '%' . PHP_EOL;
