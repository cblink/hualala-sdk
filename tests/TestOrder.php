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
        ]);
    }

    public function testOrder()
    {
        $data = [
        ];
        $result = $this->hualala->order->submitOrderNew($data);
        $this->assertTrue(\Illuminate\Support\Arr::get($result, 'code') === '000');
    }
}