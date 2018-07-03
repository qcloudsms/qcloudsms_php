<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

/**
 * 统计类
 *
 */
class SmsPullStatus
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
    public function __construct($appid, $appkey)
    {
        $this->url = "https://yun.tim.qq.com/v5/tlssmssvr/pull";
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    /**
     * 拉取短信统计
     * 
     * @param  string $type    拉取类型， send表示发送数据， callback表示回执数据
     * @param  int $begin_date 开始时间，yyyymmddhh 需要拉取的起始时间,精确到小时
     * @param  int $end_date   结束时间，yyyymmddhh 需要拉取的截止时间,精确到小时
     * @return string 应答json字符串，详细内容参见腾讯云协议文档https://cloud.tencent.com/document/product/382/7755
     */
    private function pull($type, $begin_date, $end_date)
    {
    	$random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url. $type . "status?sdkappid=" . $this->appid . "&random=" . $random;

        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->begin_date = $begin_date;
        $data->end_date = $end_date;
        
        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 拉取发送数据统计
     * 
     * @param  int $begin_date 开始时间，yyyymmddhh 需要拉取的起始时间,精确到小时
     * @param  int $end_date   结束时间，yyyymmddhh 需要拉取的截止时间,精确到小时
     * @return string 应答json字符串，详细内容参见腾讯云协议文档https://cloud.tencent.com/document/product/382/7756
     */
    public function send($begin_date, $end_date)
    {
    	return $this->pull('send', $begin_date, $end_date);
    }

    /**
     * 拉取回执数据统计
     * 
     * @param  int $begin_date 开始时间，yyyymmddhh 需要拉取的起始时间,精确到小时
     * @param  int $end_date   结束时间，yyyymmddhh 需要拉取的截止时间,精确到小时
     * @return string 应答json字符串，详细内容参见腾讯云协议文档
     */
    public function callback($begin_date, $end_date)
    {
    	return $this->pull('callback', $begin_date, $end_date);
    }
}
