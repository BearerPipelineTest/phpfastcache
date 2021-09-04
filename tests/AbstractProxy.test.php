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

use Phpfastcache\Core\Item\ExtendedCacheItemInterface;
use Phpfastcache\Tests\Helper\TestHelper;
use Phpfastcache\Proxy\PhpfastcacheAbstractProxy;

chdir(__DIR__);
require_once __DIR__ . '/../vendor/autoload.php';
$testHelper = new TestHelper('phpfastcacheAbstractProxy class');
$defaultDriver = (!empty($argv[1]) ? ucfirst($argv[1]) : 'Files');


/**
 * Dynamic driver-based example
 * Class myCustomCacheClass
 * @package MyCustom\Project
 */
class CustomMemcachedCacheClass extends PhpfastcacheAbstractProxy
{
    public function __construct($driver = '', $config = null)
    {
        global $defaultDriver;
        $driver = $defaultDriver;
        parent::__construct($driver, $config);
        /**
         * That's all !! Your cache class is ready to use
         */
    }
}


/**
 * Testing memcached as it is declared in .travis.yml
 */
$driverInstance = new CustomMemcachedCacheClass();

if (!is_object($driverInstance->getItem('test'))) {
    $testHelper->assertFail('$driverInstance->getItem() returned an invalid var type:' . gettype($driverInstance));
} elseif (!($driverInstance->getItem('test') instanceof ExtendedCacheItemInterface)) {
    $testHelper->assertFail('$driverInstance->getItem() returned an invalid class that does not implements ExtendedCacheItemInterface: ' . get_class($driverInstance));
} else {
    $testHelper->assertPass('$driverInstance->getItem() returned a valid class that implements ExtendedCacheItemInterface: ' . get_class($driverInstance));
}

$testHelper->terminateTest();
