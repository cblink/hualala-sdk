<?php


use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class TestShop extends TestCase
{
    protected $hualala;

    protected function setUp(): void
    {
        parent::setUp();

        $config = require 'testConfig.php';
        $appKey = $config['app_key'];
        $secret = $config['secret'];
        $groupID = $config['groupID'];
        $this->hualala = new \Cblink\HualalaSdk\HualalaOpen([
            'appKey' => $appKey,
            'appSecret' => $secret,
            'shopID' => '76862362',
            'groupID' => $groupID,
            'traceID' => Uuid::uuid1()->toString(),
        ]);
    }

    public function testShopList()
    {
        $result = $this->hualala->shop->getAllShop(['groupID' => 324716]);
        $this->assertTrue(\Illuminate\Support\Arr::get($result, 'code') === '000');
    }

    public function testShopGoodsList()
    {
        $result = $this->hualala->shop->getOpenFood(['groupID' => 324716, 'shopID' => 76862362]);

        $this->assertTrue(\Illuminate\Support\Arr::get($result, 'code') === '000');
    }


}