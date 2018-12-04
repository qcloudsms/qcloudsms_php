<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

/**
 * 短信签名
 *
 */
class SmsSign
{
    private $url;
    private $appid;
    private $appkey;
    private $util;

    const ADD_SIGN_URI = 'add_sign';
    const MOD_SIGN_URI = 'mod_sign';
    const DEL_SIGN_URI = 'del_sign';
    const GET_SIGN_URI = 'get_sign';

    /**
     * 构造函数
     *
     * @param string $appid  sdkappid
     * @param string $appkey sdkappid对应的appkey
     */
    public function __construct($appid, $appkey)
    {
        $this->url = "https://yun.tim.qq.com/v5/tlssmssvr/";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    public function addSmsSign($text,$remark = '',$international = 0, $pic = '')
    {
        $this->url = $this->url.self::ADD_SIGN_URI;
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->text = $text;
        $data->pic = $pic;
        $data->international = $international;
        $data->remark = $remark;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    public function modSmsSign($sign_id,$text,$remark = '',$international = 0, $pic = '')
    {
        $this->url = $this->url.self::MOD_SIGN_URI;
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->text = $text;
        $data->pic = $pic;
        $data->sign_id = $sign_id;
        $data->international = $international;
        $data->remark = $remark;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    public function delSmsSign($sign_id)
    {
        $this->url = $this->url.self::DEL_SIGN_URI;
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->sign_id = $sign_id;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    public function getSmsSign($sign_id)
    {
        $this->url = $this->url.self::GET_SIGN_URI;
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->sign_id = $sign_id;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }
}
