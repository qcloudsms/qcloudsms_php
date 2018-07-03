<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

/**
 * 短信签名类
 * 
 */
class SmsSign
{
    private $url;
    private $appid;
    private $appkey;
    private $util;

    /**
     * 构造函数
     *
     * @param string $appid  sdkappid
     * @param string $appkey sdkappid对应的appkey
     */
    function __construct($appid, $appkey)
    {
        $this->url = "https://yun.tim.qq.com/v5/tlssmssvr/";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }
    
    /**
     * 添加短信签名
     * 
     * @param string $text   签名内容，不带【】，例如：【腾讯科技】这个签名，这里填"腾讯科技"
     * @param img $pic    签名对应的资质证明图片地址
     * @param string $remark 签名备注，比如申请原因，使用场景等
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function addSign($text,  $pic = "", $remark = "")
    {
    	$random       = $this->util->getRandom();
        $curTime      = time();
        $wholeUrl     = $this->url . "add_sign?sdkappid=" . $this->appid . "&random=" . $random;

        $data         = new \stdClass();
        $data->time   = $curTime;
        $data->remark = $remark;
        $data->text   = $text;
        $data->sig    = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->pic    = $this->util->imgToBase64($pic);

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 修改短信签名
     *
     * 已审核通过的签名不允许修改。
     * 
     * @param  int $sign_id 待修改的签名对应的签名 id
     * @param  string $text    新的签名内容，不带【】，例如：改为【腾讯科技】这个签名，这里填"腾讯科技"
     * @param  img $pic     签名对应的资质证明图片地址
     * @param  string $remark  签名备注，比如申请原因，使用场景等
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function modSign($sign_id, $text, $pic = "", $remark = "")
    {
    	$random        = $this->util->getRandom();
        $curTime       = time();
        $wholeUrl      = $this->url . "mod_sign?sdkappid=" . $this->appid . "&random=" . $random;

        $data          = new \stdClass();
        $data->time    = $curTime;
        $data->remark  = $remark;
        $data->text    = $text;
        $data->sign_id = (int)$sign_id;
        $data->sig     = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->pic     = $this->util->imgToBase64($pic);

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 删除短信签名
     * 
     * @param array $sign_id 签名id，也可以通过值指定一个"sign_id":123
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function delSign($sign_ids)
    {
    	$random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "del_sign?sdkappid=" . $this->appid . "&random=" . $random;

        $data = new \stdClass();
        $data->time = $curTime;
        $data->sign_id = (array)$sign_ids;
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 签名状态查询
     * 
     * @param  array $sign_id 签名id，也可以通过值指定一个"sign_id":123
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function getSign($sign_ids)
    {
    	$random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "get_sign?sdkappid=" . $this->appid . "&random=" . $random;
        $data = new \stdClass();
        $data->time = $curTime;
        $data->sign_id = (array)$sign_ids;
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);

        return $this->util->sendCurlPost($wholeUrl, $data);
    }
}
