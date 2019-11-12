<?php

require __DIR__ . "/vendor/autoload.php";

use Qcloud\Sms\SmsSingleSender;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsVoiceVerifyCodeSender;
use Qcloud\Sms\SmsVoicePromptSender;
use Qcloud\Sms\SmsStatusPuller;
use Qcloud\Sms\SmsMobileStatusPuller;
use Qcloud\Sms\SmsSign;
use Qcloud\Sms\SmsTemplate;
use Qcloud\Sms\SmsPullStatus;

use Qcloud\Sms\VoiceFileUploader;
use Qcloud\Sms\FileVoiceSender;
use Qcloud\Sms\TtsVoiceSender;


// 短信应用SDK AppID
$appid = 1400009099; // 1400开头

// 短信应用SDK AppKey
$appkey = "9ff91d87c2cd7cd0ea762f141975d1df37481d48700d70ac37470aefc60f9bad";

// 需要发送短信的手机号码
$phoneNumbers = ["21212313123", "12345678902", "12345678903"];

// 短信模板ID，需要在短信应用中申请
$templateId = 7839;  // NOTE: 这里的模板ID`7839`只是一个示例，真实的模板ID需要在短信控制台中申请

// 签名
$smsSign = "腾讯云"; // NOTE: 这里的签名只是示例，请使用真实的已申请的签名，签名参数使用的是`签名内容`，而不是`签名ID`


// 单发短信
try {
    $ssender = new SmsSingleSender($appid, $appkey);
    $result = $ssender->send(0, "86", $phoneNumbers[0],
        "【腾讯云】您的验证码是: 5678", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 指定模板ID单发短信
try {
    $ssender = new SmsSingleSender($appid, $appkey);
    $params = ["5678"];
    $result = $ssender->sendWithParam("86", $phoneNumbers[0], $templateId,
        $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 群发
try {
    $msender = new SmsMultiSender($appid, $appkey);
    $result = $msender->send(0, "86", $phoneNumbers,
        "【腾讯云】您的验证码是: 5678", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 指定模板ID群发
try {
    $msender = new SmsMultiSender($appid, $appkey);
    $params = ["5678"];
    $result = $msender->sendWithParam("86", $phoneNumbers,
        $templateId, $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 发送语音验证码
try {
    $vvcsender = new SmsVoiceVerifyCodeSender($appid, $appkey);
    $result = $vvcsender->send("86", $phoneNumbers[0], "5678", 2, "");
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 发送语音通知
try {
    $vpsender = new SmsVoicePromptSender($appid, $appkey);
    $result = $vpsender->send("86", $phoneNumbers[0], 2, "5678", "");
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 拉取短信回执以及回复
try {
    $sspuller = new SmsStatusPuller($appid, $appkey);

    // 拉取短信回执
    $callbackResult = $sspuller->pullCallback(10);
    $callbackRsp = json_decode($callbackResult);
    echo $callbackResult;

    // 拉取回复
    $replyResult = $spuller->pullReply(10);
    $replyRsp = json_decode($replyResult);
    echo $replyResult;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 拉取单个手机短信状态
try {
    $beginTime = 1516670595;  // 开始时间(unix timestamp)
    $endTime = 1516680595;    // 结束时间(unix timestamp)
    $maxNum = 10;             // 单次拉取最大量
    $mspuller = new SmsMobileStatusPuller($appid, $appkey);

    // 拉取短信回执
    $callbackResult = $mspuller->pullCallback("86", $phoneNumbers[0],
        $beginTime, $endTime, $maxNum);
    $callbackRsp = json_decode($callbackResult);
    echo $callbackResult;
    echo "\n";

    // 拉取回复
    $replyResult = $mspuller->pullReply("86", $phoneNumbers[0],
        $beginTime, $endTime, $maxNum);
    $replyRsp = json_decode($replyResult);
    echo $replyResult;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 上传语音文件
try {
    $filepath = "path/to/example.mp3";
    $fileContent = file_get_contents($filepath);
    if ($fileContent == false) {
        throw new \Exception("can not read file " . $filepath);
    }

    $contentType = VoiceFileUploader::MP3;
    $uploader = new VoiceFileUploader($appid, $appkey);
    $result = $uploader->upload($fileContent, $contentType);
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 按语音文件fid发送语音通知
try {
    $fid = "73844bb649ca38f37e596ec2781ce6a56a2a3a1b.mp3";

    $fvsender = new FileVoiceSender($appid, $appkey);
    $result = $fvsender->send("86", $phoneNumbers[0], $fid);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 指定模板发送语音通知类
try {
    $templateId = 1013;
    $params = ["54321"];

    $tvsender = new TtsVoiceSender($appid, $appkey);
    $result = $tvsender->send("86", $phoneNumbers[0], $templateId, $params);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 添加短信签名
try {
    $text = "公司名称";
    $pic = "./sms.png";
    $remark = "公司名称";

    $ssign = new SmsSign($appid, $appkey);
    $result = $ssign->addSign($text, $pic, $remark);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 修改短信签名
// 已审核通过的签名不支持修改
try {
    $signId = 111;
    $text = "APP名称";
    $pic = "./sms.png";
    $remark = "APP名称，APP链接:https://xxxx";

    $ssign = new SmsSign($appid, $appkey);
    $result = $ssign->modSign($signId, $text, $pic, $remark);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 删除短信签名
try {
    $signIds = [111, 222];

    $ssign = new SmsSign($appid, $appkey);
    $result = $ssign->delSign($signIds);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 短信签名状态查询
try {
    $signIds = [111, 222];

    $ssign = new SmsSign($appid, $appkey);
    $result = $ssign->getSign($signIds);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 添加短信模板
try {
    $test = "您的验证码是{1}，请在{2}分钟内填写，如非本人操作，请忽略。";
    $type = 0;
    $title = "验证码";
    $remark = "发送给会员的验证码短信";

    $stemp = new SmsTemplate($appid, $appkey);
    $result = $stemp->addTemplate($test, $type, $title, $remark);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 修改短信模板
// 已审核通过的模板不支持修改
try {
    $templateId = 111;
    $test = "您的验证码是{1}，请在{2}分钟内填写，如非本人操作，请联系官网客服反馈。";
    $type = 0;
    $title = "验证码修改";
    $remark = "发送给会员的验证码短信";

    $stemp = new SmsTemplate($appid, $appkey);
    $result = $stemp->modTemplate($templateId, $test, $type, $title, $remark);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 删除短信模板
try {
    $templateIds = [111, 222];

    $stemp = new SmsTemplate($appid, $appkey);
    $result = $stemp->delTemplate($templateIds);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 短信模板状态查询
try {
    $templateIds = [111, 222];

    $stemp = new SmsTemplate($appid, $appkey);
    $result = $stemp->getTemplate($templateIds);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 短信模板状态拉取
try {
    $offset = 0;
    $max = 50;

    $stemp = new SmsTemplate($appid, $appkey);
    $result = $stemp->pullTemplate($offset, $max);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 发送数据统计
try {
    $beginDate = 2018070100;
    $endDate = 2018070323;

    $stemp = new SmsPullStatus($appid, $appkey);
    $result = $stemp->send($beginDate, $endDate);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";


// 回执数据统计
try {
    $beginDate = 2018070100;
    $endDate = 2018070323;

    $stemp = new SmsPullStatus($appid, $appkey);
    $result = $stemp->callback($beginDate, $endDate);

    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
echo "\n";