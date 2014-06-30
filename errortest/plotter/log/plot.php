<?php
$lines = include __DIR__ . '/../plotfileinclude.php';

$data = array();


foreach($lines as $line)
{
    $line = explode("\t",$line);

    $int = (int)$line[0];

    isset($data[$int]) or $data[$int] = array();

    $data[$int][] = (int)$line[1];
}

$parts = array();
foreach($data as $i => $counts)
{
    $parts[] = array($i, array_sum($data[$i])/count($data[$i]));
}

echo json_encode($parts);
