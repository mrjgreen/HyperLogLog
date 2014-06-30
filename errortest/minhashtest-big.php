<?php
include __DIR__ . '/../vendor/autoload.php';

ini_set('memory_limit','512M');

function testHeader($title)
{
    echo '-----------------------------------------------------'.PHP_EOL;
    echo $title . PHP_EOL;
    echo '-----------------------------------------------------'.PHP_EOL;
    echo '|'.implode('|',map_pad(array('Actual','Estimated','Total','Error'))).'|' . PHP_EOL;
    echo '-----------------------------------------------------'.PHP_EOL;
}

function errorLine($actual, $estimated)
{
    return number_format((($actual - $estimated) / $actual) * 100, 4) . '%';
}

function map_pad($array)
{
    return array_map(function($val){return str_pad($val, 12, ' ', STR_PAD_BOTH);}, $array);
}

function printResults($resultsArray)
{
    foreach($resultsArray as $results)
    {
        $results[] = errorLine($results[0], $results[1]);

        echo implode(" " , map_pad($results));

        echo PHP_EOL;
    }

}

function fileResults($file, $resultsArray)
{
    foreach($resultsArray as $results)
    {
        file_put_contents($file, implode("\t" , $results) . PHP_EOL, FILE_APPEND);
    }
}

if($_SERVER['argc'] < 4)
{
    die('Usage: ' . $_SERVER['argv'][0] . ' start end pValue hashKValue [number_of_tests:10]' . PHP_EOL);
}

$pValue = $_SERVER['argv'][3];
$hashKValue = $_SERVER['argv'][4];
$testMin = $_SERVER['argv'][1];
$testMax = $_SERVER['argv'][2];
$tests = isset($_SERVER['argv'][5]) ? $_SERVER['argv'][5] : 10;

$print = true;
$verbose = false;
$filename = __DIR__ . '/data/minhash/'.$testMin . '-' .$testMax.'-p'.$pValue.'-k'.$hashKValue.'.'.date('Y-m-d_h-i-s').'.csv';

file_put_contents($filename,'');

for($i = $testMin; $i <= $testMax; $i += $block)
{
    echo "Running $i..." . PHP_EOL;

    $block = pow(10, max(0,floor(log10($i))));

    $test = new Test($i, $pValue);

    $test->test($tests);

    if($print)
    {
        if($verbose) {
            testHeader('Tested: ' . $i);
            printResults($test->results());
        }
        testHeader('Average: ' . $i);
        printResults(array($test->averages()));
    }

    fileResults($filename, $test->results());

    echo "Tested $i\r";
}

echo PHP_EOL;



class Test {

    private $i;

    private $pValue;

    private $average = array(0,0,0);

    private $results = array();

    public function __construct($i, $pValue = 14)
    {
        $this->i = $i;

        $this->pValue = $pValue;
    }

    private function random()
    {
        $start = 100000000;

        return mt_rand($start, $start + 2 * $this->i);
    }

    public function test($repeat = 100)
    {
        while($repeat--)
        {
            $ll1 = new HyperLogLog\MinHash();
            $ll2 = new HyperLogLog\MinHash();

            $i = 100000000 + $this->random();

            $r = mt_rand(1,4);

            $end = $i + ($this->i * $r);

            $actual = 0;

            $overlap = 0;

            while($i <= $end)
            {
                $ll1->add($i);

                if(++$overlap === 2)
                {
                    $overlap = 0;
                    $ll2->add($i);
                    $actual++;
                }

                $i += $r;
            }

            $intersection = \HyperLogLog\Utils\MinHashIntersector::count(array($ll1,$ll2));

            $ll1->union($ll2);

            $total = $ll1->count();

            $this->average[0] += $actual;

            $this->average[1] += $intersection;

            $this->average[2] += $total;

            $this->results[] = array($actual, $intersection, $total);
        }
    }

    public function averages()
    {
        return $this->average;
    }

    public function results()
    {
        return $this->results;
    }
}