<?php
$lines = include __DIR__ . '/../plotfileinclude.php';

$data = array();

$average = array();

foreach($lines as $line)
{
    $line = explode("\t",$line);

    $int = (int)$line[0];

    $error = ($line[1] - $int) / $int;

    $data[] = array($int, round($error * 100, 2));

    isset($average[$int]) or $average[$int] = array();

    $average[$int][] = $error;
}

$parts = array();
foreach($average as $i => $counts)
{
    $parts[] = array($i, round(array_sum($counts)/count($counts) * 100,2));
}

echo json_encode(array($data, $parts));
