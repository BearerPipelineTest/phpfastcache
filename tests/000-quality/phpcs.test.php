<?php

/**
 *
 * This file is part of Phpfastcache.
 *
 * @license MIT License (MIT)
 *
 * For full copyright and license information, please see the docs/CREDITS.txt and LICENCE files.
 *
 * @author Georges.L (Geolim4)  <contact@geolim4.com>
 * @author Contributors  https://github.com/PHPSocialNetwork/phpfastcache/graphs/contributors
 */

use Phpfastcache\CacheManager;
use Phpfastcache\Tests\Helper\TestHelper;

chdir(__DIR__);
require_once __DIR__ . '/../../vendor/autoload.php';
$testHelper = new TestHelper('Quality: PHPCS');

//
chdir(__DIR__ . '/../../');

$binary = realpath(getcwd() . '/vendor/bin/phpcs');

exec($binary . ' lib/  --report=summary', $output, $resultCode);

if ($resultCode === 0) {
    $testHelper->assertPass('Great, PHPCS found no errors on the project');
} else {
    $testHelper->assertFail('Oh no, PHPCS found some errors on the project, full report available below:');
    $testHelper->printText(str_repeat('#', 100));
    $testHelper->printText(array_map(static fn ($str) => $str ? '    ' . $str : '', $output));
    $testHelper->printText(str_repeat('#', 100));
}

$testHelper->terminateTest();