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
            'shopID' => '123123',
            'groupID' => $groupID,
            'traceID' => Uuid::uuid1()->toString(),
        ]);
    }

    public function testShopList()
    {
        $result = $this->hualala->shop->getAllShop(['groupID' => 1]);
        $this->assertTrue(\Illuminate\Support\Arr::get($result, 'code') === '000');
    }

    public function testShopGoodsList()
    {
        $result = $this->hualala->shop->getOpenFood(['groupID' => 1, 'shopID' => 1]);

        $this->assertTrue(\Illuminate\Support\Arr::get($result, 'code') === '000');
    }

    public function testGroupGoods()
    {
        $result = $this->hualala->shop->groupGoods(['groupID' => 1, 'pageNo' => 1, 'pageSize' => 10]);
        $this->assertTrue(\Illuminate\Support\Arr::get($result, 'code') === '000');
    }

}