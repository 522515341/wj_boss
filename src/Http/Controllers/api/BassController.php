<?php

namespace Weigatherboss\BossLogin\Http\Controllers\Api;

use Illuminate\Support\Facades\Request;

// use Encore\Admin\Layout\Content;
// use Illuminate\Routing\Controller;

class BassController
{
    private $appId;
    private $appSecret;
    private $appHost;
    public $lang = "zh-CN";// en

    public function __construct()
    {
        $this->appId = config('wj_boss_login_service.user_center.app_id');
        $this->appSecret = config('wj_boss_login_service.user_center.app_secret');
        $this->appSecret = config('wj_boss_login_service.user_center.app_host');
    }
    /**
     * 设置语言包 暂时支持  zh-CN en
     * @param $lang
     */
    public function setLang($lang){
        $this->lang = $lang;
    }
    // 应用签名
    public function appIdSign($data = [])
    {
        $data['app_id'] = $this->appId;
        $data['timestamp'] = time();
        $data['nonce_str'] = $this->getNonceStr();
        $data['sign'] = $this->sign($data, $this->appSecret);
        return $data;
    }
    /**
     * 生成签名
     * @param $data
     * @param $key
     * @return string
     */
    public function sign($data, $key)
    {
        ksort($data);
        $data['key'] = $key;
        return md5(http_build_query($data));
    }
    /*
     * 生成随机字符串
     */
    public function getNonceStr($length = 8)
    {
        $charts = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz0123456789";
        $max = strlen($charts);
        $noncestr = "";
        for ($i = 0; $i < $length; $i++) {
            $noncestr .= $charts[mt_rand(0, $max - 1)];
        }
        return $noncestr;
    }
}