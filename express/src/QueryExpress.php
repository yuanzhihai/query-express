<?php
/**
 * Created by dh2y.
 * Date: 2018/10/15 16:20
 * for: 快递查询
 */

namespace yzh52521\query\express;


use think\facade\Config;

class QueryExpress
{

    private $config = [
        'type_url'  => 'http://www.kuaidi100.com/autonumber/autoComNum?text=',
        'query_url' => 'http://www.kuaidi100.com/query?'
    ];

    private $express = [
        'youzhengguonei' => '邮政快递包裹',
        'ems'            => 'EMS',
        'shunfeng'       => '顺丰快递',
        'shentong'       => '申通快递',
        'yuantong'       => '圆通快递',
        'zhongtong'      => '中通快递',
        'huitongkuaidi'  => '汇通快递',
        'yunda'          => '韵达快递',
        'zhaijisong'     => '宅急送',
        'tiantian'       => '天天快递',
        'debangwuliu'    => '德邦快递',
        'guotongkuaidi'  => '国通快递',
        'jd'             => '京东物流',
        'annengwuliu'    => '安能物流',
        'youshuwuliu'    => '优速快递',
        'quanfengkuaidi' => '全峰快递'
    ];


    private static $instance = null;  //创建静态单列对象变量

    private $error = '';

    /**
     * QueryExpress constructor.
     * @param array $express
     */
    private function __construct($express = array())
    {
        if (empty($express) && $config = Config::get('express')) {
            $express = $config;
        }
        /* 获取配置 */
        $this->express = array_merge($this->express, $express);
    }

    /**
     * 单列模式
     * @param array $config
     * @return QueryExpress|null
     */
    public static function getInstance($config = array())
    {
        if (empty(self::$instance)) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * 克隆函数私有化，防止外部克隆对象
     * @throws \Exception
     */
    private function __clone()
    {
        throw new \Exception('禁止克隆');
    }

    /**
     * 返还错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 设置错误信息
     * @param $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    public function __set($name, $value)
    {
        $this->config[$name] = $value;
    }

    public function __get($name)
    {
        return $this->config[$name];
    }


    /**
     * 获取快递公司
     * @param  string $num 快递单号
     * @return array|bool
     */
    public function getType($num)
    {
        $request = $this->type_url . $num;
        $result = $this->http('get',$request);
        $result = json_decode($result, JSON_OBJECT_AS_ARRAY);

        $return = [];
        if (isset($result['auto'][0])) {
            $return['type'] = $result['auto'][0]['comCode'];
            $return['num'] = $num;
            $return['name'] = isset($this->express[$return['type']]) ? $this->express[$return['type']] : $return['type'];
        }
        return count($return) > 0 ? $return : false;
    }

    /**
     * 获取快递公司代码
     *
     * @param  string $num 快递单号
     * @return string
     */
    public function getComCode($num)
    {
        $request = $this->type_url . $num;
        $result = $this->http('get',$request);
        $result = json_decode($result, JSON_OBJECT_AS_ARRAY);
        $comCode = '';
        if (isset($result['auto'][0])) {
            $comCode = $result['auto'][0]['comCode'];
        }
        return $comCode;
    }

    /**
     * 快递详情
     *
     * @param string $num 快递单号
     * @return array|bool   state 0：在途中,1：已发货，2：疑难件，3： 已签收 ，4：已退货。
     */
    public function details($num)
    {
        $type = $this->getComCode($num);
        $request = $this->query_url . "type=$type&postid=$num";
        $result = $this->http('get',$request);
        $result = json_decode($result, JSON_OBJECT_AS_ARRAY);
        $detail = [];
        if ($result['status'] == 200) {
            $detail['data'] = $result['data'];
            $detail['type'] = $result['com'];
            $detail['name'] = isset($this->express[$result['com']]) ? $this->express[$result['com']] : $result['com'];
            $detail['num'] = $result['nu'];
            $detail['state'] = $result['state'];
            switch ($result['state']) {
                case 0:
                    $detail['ret'] = '在途中';
                    break;
                case 1:
                    $detail['ret'] = '已发货';
                    break;
                case 2:
                    $detail['ret'] = '疑难件';
                    break;
                case 3:
                    $detail['ret'] = '已签收';
                    break;
                case 4:
                    $detail['ret'] = '已退货';
                    break;
                default:
                    $detail['ret'] = '未知状态';
                    break;
            }
        } else {
            $this->setError($result['message']);
        }
        return count($detail) > 0 ? $detail : false;
    }


    /**
     * 快递状态
     *
     * @param string $num 快递单号
     * @return array state 0：在途中,1：已发货，2：疑难件，3： 已签收 ，4：已退货。
     */
    public function getState($num)
    {
        $type = $this->getComCode($num);
        $request = $this->query_url . "type=$type&postid=$num";
        $result = $this->http('get',$request);
        $result = json_decode($result, JSON_OBJECT_AS_ARRAY);
        $status = ['state' => null, 'ret' => ''];
        if ($result['status'] == 200) {
            switch ($result['state']) {
                case 0:
                    $status['ret'] = '在途中';
                    break;
                case 1:
                    $status['ret'] = '已发货';
                    break;
                case 2:
                    $status['ret'] = '疑难件';
                    break;
                case 3:
                    $status['ret'] = '已签收';
                    break;
                case 4:
                    $status['ret'] = '已退货';
                    break;
                default:
                    $status['ret'] = '未知状态';
                    break;
            }
            $status['state'] = $result['state'];
        }
        return $status;
    }

    /**
     * CURL模拟网络请求
     *
     * @param string $method 请求方法
     * @param string $url 请求方法
     * @param array $options 请求参数[headers,data,ssl_cer,ssl_key]
     * @return bool|string
     */
    private function http($method, $url, $options = [])
    {
        $curl = curl_init();
        // GET参数设置
        if (!empty($options['query'])) {
            $url .= (stripos($url, '?') !== false ? '&' : '?') . http_build_query($options['query']);
        }
        // CURL头信息设置
        if (!empty($options['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        }
        // POST数据设置
        if (strtolower($method) === 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $options['data']);
        }
        // 证书文件设置
        if (!empty($options['ssl_cer'])) {
            if (file_exists($options['ssl_cer'])) {
                curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLCERT, $options['ssl_cer']);
            } else {
                throw new InvalidArgumentException("Certificate files that do not exist. --- [ssl_cer]");
            }
        }
        // 证书文件设置
        if (!empty($options['ssl_key'])) {
            if (file_exists($options['ssl_key'])) {
                curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLKEY, $options['ssl_key']);
            } else {
                throw new InvalidArgumentException("Certificate files that do not exist. --- [ssl_key]");
            }
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        list($content, $status) = [curl_exec($curl), curl_getinfo($curl), curl_close($curl)];
        return (intval($status["http_code"]) === 200) ? $content : false;
    }

}