<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

class SmsVoiceVerifyCodeSender
{
    private $url;
    private $appid;
    private $appkey;
    private $util;

    public function __construct($appid, $appkey)
    {
        $this->url = "https://yun.tim.qq.com/v5/tlsvoicesvr/sendvoice";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    /**
     * 语言验证码发送
     *
     * 请求包体：
     * {
     *   "tel": {
     *     "nationcode": "86",
     *     "mobile": "13788888888"
     *   },
     *  "msg": "1234",
     *  "playtimes": 2,
     *  "sig": "30db206bfd3fea7ef0db929998642c8ea54cc7042a779c5a0d9897358f6e9505",
     *  "time": 1457336869,
     *  "ext": ""
     * }
     *
     * 应答包体：
     * {
     *   "result": 0,
     *   "errmsg": "OK",
     *   "ext": "",
     *   "sid": "xxxxxxx",
     *   "fee": 1
     * }
     *
     * @param string $nationCode  国家码，如 86 为中国
     * @param string $phoneNumber 不带国家码的手机号
     * @param string $msg         信息内容，必须与申请的模板格式一致，否则将返回错误
     * @param int    $playtimes   播放次数，可选，最多3次，默认2次
     * @param string $ext         用户的session内容，服务端原样返回，可选字段，不需要可填空串
     * @return string json字符串，格式参考"应答包体"，详细内容参见协议文档
     */
    public function send($nationCode, $phoneNumber, $msg, $playtimes = 2, $ext = "")
    {
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?sdkappid=" . $this->appid . "&random=" . $random;

        // 按照协议组织 post 包体
        $data = new \stdClass();
        $tel = new \stdClass();
        $tel->nationcode = "".$nationCode;
        $tel->mobile = "".$phoneNumber;

        $data->tel = $tel;
        $data->msg = $msg;
        $data->playtimes = $playtimes;
        // app凭证
        $data->sig = hash("sha256",
            "appkey=".$this->appkey."&random=".$random."&time="
            .$curTime."&mobile=".$phoneNumber, FALSE);
        // unix时间戳，请求发起时间，如果和系统时间相差超过10分钟则会返回失败
        $data->time = $curTime;
        $data->ext = $ext;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }
}
