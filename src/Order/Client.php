<?php

/*
 * This file is part of the cblinkservice//meituan-dispatch-service.
 *
 * (c) jinjun <757258777@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\HualalaSdk\Order;


use Cblink\HualalaSdk\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 下单
     *
     * @see https://www-openapi.hualala.com/order/submitordernew
     *
     * @param array $payload
     * @return array
     */
    public function submitOrderNew(array $payload = [])
    {
        return $this->sendRequest('post', 'order/submitordernew', $payload);
    }

    /**
     * 下单支持加菜.
     *
     * @see https://www-openapi.hualala.com/order/createOrder
     * @param array $payload
     * @return array
     */
    public function createOrder(array $payload = [])
    {
        return $this->sendRequest('post', 'order/createOrder', $payload);
    }

    /**
     * 订单详情
     *
     * @see https://www-openapi.hualala.com/order/createOrder
     * @param array $payload
     * @return mixed
     */
    public function queryOrder(array $payload = [])
    {
        return $this->sendRequest('post', 'order/query', $payload);
    }

    /**
     * 解密
     *
     * @param string $payload
     * @return mixed
     */
    public function dataDecrypt(string $payload='')
    {
        return $this->decrypt($payload);
    }

    /**
     * 加密
     *
     * @param array $payload
     * @return mixed
     */
    public function dataEncrypt(array $payload=[])
    {
        return $this->encrypt($payload);
    }

}
