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

    /**
     * 添加签名
     *
     * @param string $text  签名内容，不带【】，例如：【腾讯科技】这个签名，这里填"腾讯科技"
     * @param string $remark    签名备注，比如申请原因，使用场景等
     * @param int $international    0表示国内短信，1表示海外短信，默认为0
     * @param string $pic   签名对应的资质证明图片进行 base64 编码格式转换后的字符串base64 编码格式工具: http://base64.xpcha.com/indexie.php ，注意编译后去掉字符串前面的前缀：“data:image/jpeg;base64,”
     * @return string
     */
    public function addSmsSign($text, $remark = '', $international = 0, $pic = '')
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

    /**
     *  修改签名
     *
     * @param int $sign_id  待修改的签名对应的签名 id
     * @param string $text  新的签名内容，不带【】，例如：改为【腾讯科技】这个签名，这里填"腾讯科技"
     * @param string $remark    新的签名备注，比如申请原因，使用场景等
     * @param string $pic   签名对应的资质证明图片进行 base64 编码格式转换后的字符串base64 编码格式工具: http://base64.xpcha.com/indexie.php ，注意编译后去掉字符串前面的前缀：“data:image/jpeg;base64,”
     * @return string
     */
    public function modSmsSign($sign_id, $text, $remark = '', $pic = '')
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
        $data->remark = $remark;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     *  删除签名
     *
     * @param array $sign_id    签名 id，也可以通过值指定一个 "sign_id"：123
     * @return string
     */
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

    /**
     *  短信签名状态查询
     *
     * @param array $sign_id    签名 id，也可以通过值指定一个 "sign_id"：123
     * @return string
     */
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
