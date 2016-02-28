<?php

use Mobileka\Liner\Liner;

require __DIR__ . '/vendor/autoload.php';

// consists of 10 lines
$file = __DIR__ . '/tests/resources/words';
$liner = new Liner($file);
$time = [];

for ($i = 0; $i < 1000; $i++) {
    $time[] = run($liner);
}

// time to read a 1000 line file
$average = array_sum($time) / 1000;
echo $average . PHP_EOL;

/**
 * @param Liner $liner
 * @return float
 */
function run($liner)
{
    $start = microtime(true);

    for ($i = 0; $i < 100; $i++) {
        $liner->read();
    }

    return microtime(true) - $start;
}
