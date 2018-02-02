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
        $this->url = [
        	"send" => "https://yun.tim.qq.com/v5/tlssmssvr/pullsendstatus",
        	"callback" => "https://yun.tim.qq.com/v5/tlssmssvr/pullcallbackstatus"
        ];
        $this->appid =  $appid;
        $this->appkey = $appkey;
        $this->util = new SmsSenderUtil();
    }

    private function pull($type, $begin_date, $end_date)
    {
    	$random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url["$type"] . "?sdkappid=" . $this->appid . "&random=" . $random;

        $data = new \stdClass();
        $data->sig = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;
        $data->begin_date = $begin_date;
        $data->end_date = $end_date;
        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    public function pullSend($begin_date, $end_date)
    {
    	return $this->pull('send', $begin_date, $end_date);
    }

    public function pullCallback($begin_date, $end_date)
    {
    	return $this->pull('callback', $begin_date, $end_date);
    }

}
