<?php

ini_set('memory_limit','1G');

function randomSet($count = 10000, $randomness = null) {
    $randomness or $randomness = $count * 0.75;
    $result = array();

    while ($count > 0) {
        $count--;
        $result[] = rand(10000,10000 + $randomness);
    }

    return $result;
}

function cardinality($arr) {
    $arr = array_count_values($arr);
    return count($arr);
}