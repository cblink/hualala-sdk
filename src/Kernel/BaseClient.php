<?php

/*
 * This file is part of the cblink/dispatch-meituan.
 *
 * (c) jinjun <757258777@qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Cblink\HualalaSdk\Kernel;

use Cblink\Hualala\HualalaOptions;
use Cblink\HualalaSdk\Kernel\Exception\HualalaException;
use Cblink\HualalaSdk\Kernel\Traits\HasHttpRequest;
use http\Env\Request;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;

/**
 * Class BaseClient.
 */
class BaseClient
{
    use HasHttpRequest;

    /**
     * @var ServiceContainer
     */
    protected $app;

    protected $config;

    /**
     * BaseClient constructor.
     * @param ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;

        $this->config = $app->config;
    }

    protected function getBaseOptions()
    {
        $options = [
            'base_uri' => method_exists($this, 'getBaseUri') ? $this->getBaseUri() : '',
            'timeout' => method_exists($this, 'getTimeout') ? $this->getTimeout() : 10.0,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        return $options;
    }

    /**
     * 发送请求
     *
     * @param $method
     * @param $uri
     * @param array $params
     *
     * @return mixed
     */
    public function sendRequest($method, $uri, $params = [])
    {
        // 1. 公告参数
        $base = [
            'timestamp' => time() * 1000,
            'version' => '2.0',
            'appKey' => $this->config['appKey'],
        ];
        // 2. 业务请求
        $requiredParam = [
            'signature' => $this->signature($params, $base),
            'requestBody' => $this->encrypt($params)
        ];
        // 3. 头部参数
        $headerParam = [
            'groupID' => $this->config['groupID'],
            'traceID' => $this->config['traceID'],
        ];
        if ($this->config['shopID'] ?? null) {
            $headerParam['shopID'] = $this->config['shopID'];
        }

        $response = $this->$method($this->url($uri), array_merge($requiredParam, $base), $headerParam);

        if ($response['code'] != '000') {
            throw new HualalaException($response['message']);
        }
        return $response;
    }

    /**
     * url
     *
     * @param string $uri
     * @return string
     */
    protected function url($uri = ''): string
    {
        return rtrim($this->app->baseUrl(), '/') . '/' . ltrim($uri, '/');
    }

    /**
     * 加密 requestBody.
     *
     * @param array
     *
     * @return string
     */
    public function encrypt($param)
    {
        $json = json_encode($param);

        $encrypted = openssl_encrypt($json, 'AES-128-CBC', $this->config['appSecret'], OPENSSL_RAW_DATA, $this->config['appSecret']);

        return base64_encode($encrypted);
    }

    /**
     * 解密
     *
     * @param $param
     *
     * @return mixed
     */
    public function decrypt($param)
    {
        $value = base64_decode($param);

        $result = openssl_decrypt($value, 'AES-128-CBC', $this->config['appSecret'], OPENSSL_NO_PADDING, $this->config['appSecret']);

        return json_decode(trim($result, "\x00"), true);
    }

    /**
     * @param array $params
     * @param array $base
     *
     * @return array
     */
    public function signature(array $params, $base)
    {
        $base['appSecret'] = $this->config['appSecret'];

        $params = $this->filterParam(array_merge($params, $base));

        $params = $this->resetArray($params);

        $params = $this->sortParam($params);

        $paramsString = $this->arrayToString($params);

        return strtoupper(sha1($paramsString));
    }

    /**
     * 重新按要求组装参数
     *
     * @param $params
     * @return array
     */
    protected function resetArray($params)
    {
        $reset = [];
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                if (is_array($value[0] ?? null)) {
                    $reset = array_merge($reset, $value[0]);
                } else {
                    $reset = array_merge($reset, $this->resetArray($value));
                }
            } else {
                $reset[$key] = $value;
            }
        }
        return $reset;
    }

    /**
     * 去除空参数
     *
     * @param $param
     * @param bool $f
     * @return array
     */
    protected function filterParam($param, $f = true)
    {
        return array_filter($param, function ($item) {
            return !is_null($item);
        });
    }

    /**
     * 排序
     *
     * @param $param
     * @return mixed
     */
    public function sortParam($param)
    {
        ksort($param, SORT_STRING | SORT_FLAG_CASE);
        foreach ($param as $key => $value) {
            if (is_array($value)) {
                $param[$key] = $this->sortParam($value);
            }
        }
        return $param;
    }

    public function arrayToString($param)
    {
        $stringParam = '';
        foreach ($param as $key => $value) {
            $stringParam .= sprintf('%s%s', $key, $value);
        }
        return sprintf('key%ssecret', $stringParam);
    }
}
