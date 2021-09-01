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
declare(strict_types=1);

namespace Phpfastcache\Drivers\Devrandom;

use DateInterval;
use DateTime;
use Phpfastcache\Core\Pool\{ExtendedCacheItemPoolInterface, TaggableCacheItemPoolTrait};
use Phpfastcache\Entities\DriverStatistic;
use Phpfastcache\Exceptions\{PhpfastcacheInvalidArgumentException};
use Psr\Cache\CacheItemInterface;


/**
 * Class Driver
 * @package phpFastCache\Drivers
 * @property Config $config Config object
 * @method Config getConfig() Return the config object
 */
class Driver implements ExtendedCacheItemPoolInterface
{
    use TaggableCacheItemPoolTrait;

    /**
     * @return bool
     */
    public function driverCheck(): bool
    {
        return true;
    }

    /**
     * @return DriverStatistic
     */
    public function getStats(): DriverStatistic
    {
        $stat = new DriverStatistic();
        $stat->setInfo('[Devfalse] A void info string')
            ->setSize(0)
            ->setData(implode(', ', array_keys($this->itemInstances)))
            ->setRawData(false);

        return $stat;
    }

    /**
     * @param CacheItemInterface $item
     * @return bool
     * @throws PhpfastcacheInvalidArgumentException
     */
    protected function driverWrite(CacheItemInterface $item): bool
    {
        $this->assertCacheItemType($item, Item::class);

        return true;
    }

    /**
     * @param CacheItemInterface $item
     * @return array
     */
    protected function driverRead(CacheItemInterface $item): ?array
    {
        $chanceOfRetrieval = $this->getConfig()->getChanceOfRetrieval();
        $ttl = $this->getConfig()->getDefaultTtl();

        if(\random_int(0, 100) < $chanceOfRetrieval){
            return [
                self::DRIVER_DATA_WRAPPER_INDEX => \bin2hex(\random_bytes($this->getConfig()->getDataLength())),
                self::DRIVER_TAGS_WRAPPER_INDEX => [],
                self::DRIVER_EDATE_WRAPPER_INDEX => (new DateTime())->add(new DateInterval("PT{$ttl}S")),
            ];
        }

        return null;
    }

    /**
     * @param CacheItemInterface $item
     * @return bool
     * @throws PhpfastcacheInvalidArgumentException
     */
    protected function driverDelete(CacheItemInterface $item): bool
    {
        $this->assertCacheItemType($item, Item::class);

        return true;
    }

    /**
     * @return bool
     */
    protected function driverClear(): bool
    {
        return true;
    }

    /********************
     *
     * PSR-6 Extended Methods
     *
     *******************/

    /**
     * @return bool
     */
    protected function driverConnect(): bool
    {
        return true;
    }
}