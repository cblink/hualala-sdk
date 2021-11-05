<?php


namespace Cblink\HualalaSdk;


use Cblink\HualalaSdk\Kernel\ServiceContainer;

/**
 * @property Order\Client $order
 * @property Shop\Client $shop
 * Class HualalaOpen
 * @package Cblink\HualalaSdk
 */
class HualalaOpen extends ServiceContainer
{
    /**
     * @var string
     */
    protected $base_url = 'https://www-openapi.hualala.com/';

    /**
     * {@inheritdoc}
     */
    protected function getCustomProviders(): array
    {
        return [
            Order\ServiceProvider::class,
            Shop\ServiceProvider::class,
        ];
    }
}