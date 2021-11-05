<?php

/*
 * This file is part of the cblink/dispatch-meituan.
 *
 * (c) jinjun <757258777@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\HualalaSdk\Shop;

use Cblink\HualalaSdk\Kernel\BaseClient;

class Client extends BaseClient
{
    /**
     * 查询门店列表信息.
     *
     * @see https://www-openapi.hualala.com/doc/getAllShop
     * @param array $payload
     * @return array
     */
    public function getAllShop(array $payload = [])
    {
        return $this->sendRequest('post', 'doc/getAllShop', $payload);
    }

    /**
     * 查询门店列表信息.
     *
     * @see https://www-openapi.hualala.com/doc/getOpenFood
     * @param array $payload
     * @return array
     */
    public function getOpenFood(array $payload = [])
    {
        return $this->sendRequest('post', 'doc/getOpenFood', $payload);
    }
}
