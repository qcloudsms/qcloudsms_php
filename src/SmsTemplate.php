<?php

namespace Qcloud\Sms;

use Qcloud\Sms\SmsSenderUtil;

/**
 * 短信模板类
 *
 */
class SmsTemplate
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
        $this->url = "https://yun.tim.qq.com/v5/tlssmssvr/";
        $this->appid  =  $appid;
        $this->appkey = $appkey;
        $this->util   = new SmsSenderUtil();
    }

    /**
     * 添加短信模板
     *
     * 模板审核通过，国内，海外均可使用。
     * 
     * @param string  $text   模板内容
     * @param int     $type   模板类型，0：普通短信模板；1：营销短信模板；2：语音短信模板
     * @param string  $title  模板名称，可选字段
     * @param string  $remark 模板备注，比如申请原因，使用场景等，可选字段
     * @return string 应答json字符串，详细内容参见腾讯云协议文档https://cloud.tencent.com/document/product/382/5817
     */
    public function addTemplate($text, $type = 0, $title = "", $remark = "")
    {
        $random       = $this->util->getRandom();
        $curTime      = time();
        $wholeUrl     = $this->url. "add_template?sdkappid=" . $this->appid . "&random=" . $random;
        
        $data         = new \stdClass();
        $data->text   = $text;
        $data->type   = (int)$type;
        $data->title  = $title;
        $data->remark = $remark;
        $data->sig    = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time   = $curTime;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 修改短信模板
     *
     * 已审核通过的模板不允许修改。
     * 
     * @param  int     $tpl_id 待修改的模板的模板 id
     * @param  string  $text   新的模板内容
     * @param  string  $type   新的模板类型，0：普通短信模板；1：营销短信模板；2：语音短信模板
     * @param  string  $title  新的模板名称，可选字段
     * @param  string  $remark 新的模板备注，比如申请原因，使用场景等，可选字段
     * @return string  应答json字符串，详细内容参见腾讯云协议文档https://cloud.tencent.com/document/product/382/8649
     */
    public function modTemplate($tpl_id, $text, $type = 0, $title = "", $remark = "")
    {
        $random       = $this->util->getRandom();
        $curTime      = time();
        $wholeUrl     = $this->url . "mod_template?sdkappid=" . $this->appid . "&random=" . $random;
        
        $data         = new \stdClass();
        $data->tpl_id = (int)$tpl_id;
        $data->text   = $text;
        $data->type   = (int)$type;
        $data->title  = $title;
        $data->remark = $remark;
        $data->sig    = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time   = $curTime;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 删除短信模板
     * @param  array $tpl_ids 模板id，也可以通过值指定一个"tpl_id"：123
     * @return string  应答json字符串，详细内容参见腾讯云协议文档https://cloud.tencent.com/document/product/382/5818
     */
    public function delTemplate($tpl_ids)
    {
        $random       = $this->util->getRandom();
        $curTime      = time();
        $wholeUrl     = $this->url . "del_template?sdkappid=" . $this->appid . "&random=" . $random;
        
        $data         = new \stdClass();
        $data->tpl_id = (array)$tpl_ids;
        $data->sig    = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time   = $curTime;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }

    /**
     * 短信模板状态查询
     * 
     * @param  array $tpl_ids 模板id，也可以通过值指定一个"tpl_id"：123
     * @return string  应答json字符串，详细内容参见腾讯云协议文档https://cloud.tencent.com/document/product/382/5819
     */
    public function getTemplate($tpl_ids)
    {
        $random       = $this->util->getRandom();
        $curTime      = time();
        $wholeUrl     = $this->url . "get_template?sdkappid=" . $this->appid . "&random=" . $random;
        
        $data         = new \stdClass();
        $data->tpl_id = (array)$tpl_ids;
        $data->sig    = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time   = $curTime;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }
    
    /**
     * 短信模板状态拉取
     * 
     * @param  integer $offset 拉取的偏移量，初始为 0，如果要多次拉取，需赋值为上一次的 offset 与 max 字段的和
     * @param  integer $max    一次拉取的条数，最多 50
     * @return string  应答json字符串，详细内容参见腾讯云协议文档https://cloud.tencent.com/document/product/382/5819
     */
    public function pullTemplate($offset = 0, $max = 50)
    {
        $random     = $this->util->getRandom();
        $curTime    = time();
        $wholeUrl   = $this->url . "get_template?sdkappid=" . $this->appid . "&random=" . $random;
        
        $data       = new \stdClass();
        $tpl_page   = new \stdClass();
        $tpl_page->max = (int)$max;
        $tpl_page->offset = (int)$offset;

        $data->tpl_page = $tpl_page;
        $data->sig  = $this->util->calculateSigForPuller($this->appkey, $random, $curTime);
        $data->time = $curTime;

        return $this->util->sendCurlPost($wholeUrl, $data);
    }
}