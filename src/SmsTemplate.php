<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

/**
 * 短信模板
 *
 */
class SmsTemplate
{
    private $url;
    private $appid;
    private $appkey;
    private $util;

    const ADD_TEMPL_URI = 'add_template';
    const MOD_TEMPL_URI = 'mod_template';
    const DEL_TEMPL_URI = 'del_template';
    const GET_TEMPL_URI = 'get_template';

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

    public function addSmsTemplate($text, $type, $remark = '',$international = 0, $title = '')
    {
        $this->url = $this->url . self::ADD_TEMPL_URI;
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->text = $text;
        $data->title = $title;
        $data->type = $type;
        $data->international = $international;
        $data->remark = $remark;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    public function modSmsTemplate($tpl_id, $text, $type, $remark = '',$international = 0, $title = '')
    {
        $this->url = $this->url . self::MOD_TEMPL_URI;
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->text = $text;
        $data->type = $type;
        $data->title = $title;
        $data->tpl_id = $tpl_id;
        $data->international = $international;
        $data->remark = $remark;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    public function delSmsTemplate($tpl_id)
    {
        $this->url = $this->url . self::DEL_TEMPL_URI;
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->tpl_id = $tpl_id;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    public function getSmsTemplate($tpl_id)
    {
        $this->url = $this->url.self::GET_TEMPL_URI;
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->tpl_id = $tpl_id;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }
}
