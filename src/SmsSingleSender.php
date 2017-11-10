<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

class SmsSingleSender
{
    private $url;
    private $appid;
    private $appkey;
    private $util;

    public function __construct($appid, $appkey)
    {
        $this->url = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    /**
     * 普通单发
     *
     * 普通单发需明确指定内容，如果有多个签名，请在内容中以【】的方式添加到信息内容中，否则系统将使用默认签名。
     *
     * 请求包体：
     * {
     *   "tel": {
     *     "nationcode": "86",
     *     "mobile": "13788888888"
     *   },
     *   "type": 0,
     *   "msg": "你的验证码是1234",
     *   "sig": "fdba654e05bc0d15796713a1a1a2318c",
     *   "time": 1479888540,
     *   "extend": "",
     *   "ext": ""
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
     * @param int    $type        短信类型，0 为普通短信，1 营销短信
     * @param string $nationCode  国家码，如 86 为中国
     * @param string $phoneNumber 不带国家码的手机号
     * @param string $msg         信息内容，必须与申请的模板格式一致，否则将返回错误
     * @param string $extend      扩展码，可填空串
     * @param string $ext         服务端原样返回的参数，可填空串
     * @return string json字符串，格式参考"应答包体"，详细内容参见协议文档
     */
    public function send($type, $nationCode, $phoneNumber, $msg, $extend = "", $ext = "")
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
        $data->type = (int)$type;
        $data->msg = $msg;
        $data->sig = hash("sha256",
            "appkey=".$this->appkey."&random=".$random."&time="
            .$curTime."&mobile=".$phoneNumber, FALSE);
        $data->time = $curTime;
        $data->extend = $extend;
        $data->ext = $ext;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 指定模板单发
     *
     * 请求包体：
     * {
     *   "tel": {
     *     "nationcode": "86",
     *     "mobile": "13788888888"
     *   },
     *   "sign": "腾讯云",
     *   "tpl_id": 19,
     *   "params": [
     *     "验证码",
     *     "1234",
     *     "4"
     *   ],
     *   "sig": "fdba654e05bc0d15796713a1a1a2318c",
     *   "time": 1479888540,
     *   "extend": "",
     *   "ext": ""
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
     * @param int    $templId     模板 id
     * @param array  $params      模板参数列表，如模板 {1}...{2}...{3}，那么需要带三个参数
     * @param string $sign        签名，如果填空串，系统会使用默认签名
     * @param string $extend      扩展码，可填空串
     * @param string $ext         服务端原样返回的参数，可填空串
     * @return string json字符串，格式参考"应答包体"，详细内容参见协议文档
     */
    public function sendWithParam($nationCode, $phoneNumber, $templId = 0, $params,
        $sign = "", $extend = "", $ext = "")
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
        $data->sig = $this->util->calculateSigForTempl($this->appkey, $random,
            $curTime, $phoneNumber);
        $data->tpl_id = $templId;
        $data->params = $params;
        $data->sign = $sign;
        $data->time = $curTime;
        $data->extend = $extend;
        $data->ext = $ext;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }
}
