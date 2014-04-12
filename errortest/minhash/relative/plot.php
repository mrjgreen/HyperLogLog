<?php
$lines = include __DIR__ . '/../plotfileinclude.php';

$data = array();

$average = array();

foreach($lines as $line)
{
    $line = explode("\t",$line);

    $int = (int)$line[0];

    if(!$int) continue;

    $error = ($line[1] - $int) / $int;

    $data[] = array($int, round($error * 100, 2));

    isset($average[$int]) or $average[$int] = array();

    $average[$int][] = $error;
}

$parts = array();
foreach($average as $i => $counts)
{
    $parts[] = array($i, array_sum($counts)/count($counts));
}

echo json_encode(array($data, $parts));
