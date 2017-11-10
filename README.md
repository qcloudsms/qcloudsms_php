腾讯云短信 PHP SDK
===

# Overview

> 目前腾讯云短信为客户提供国内短信，海外短信，语音通知三大服务。

> 国内短信提供单发，群发，带模板ID单发，带模板ID群发以及短信回执与回复拉取。

> 海外短信和国内短信使用同一接口，只需替换相应的国家码与手机号码，每次请求群发接口手机号码需全部为国内或者海外手机号码。

> 语音通知目前支持语音验证码以及语音通知功能。

# Getting Start

## 准备

- [ ] 申请APPID以及APPKey

> 在开始本教程之前，您需要先获取APPID和APPkey，如您尚未申请，请到https://console.qcloud.com/sms/smslist 中添加应用，应用添加成功后您将获得APPID以及APPKey，注意APPID是以14xxxxx开头。

- [ ] 申请签名

> 下发短信必须携带签名，在相应服务模块 *短信内容配置*  中进行申请。

- [ ] 申请模板

> 下发短信内容必须经过审核，在相应服务 *短信内容配置* 中进行申请。

完成以上三项便可开始代码开发。

## 安装

### Composer

qcloudsms_php采用composer进行安装，要使用qcloudsms功能，只需要在composer.json中添加如下依赖：

```json
{
  "require": {
    "qcloudsms/qcloudsms_php": "0.1.*"
  }
}
```

### 手动

1. 手动下载或clone最新版本qcloudsms_php代码
2. 把qcloudsms_php src目录下的代码放入Autoloading目录

## 用法

> 若您对接口存在疑问，可以查阅[API文档](https://qcloudsms.github.io/qcloudsms_php/)。

- **准备必要参数**

```php
$appid = 122333333;
$appkey = "111111111112132312xx";
$phoneNumber1 = "21212313123";
$phoneNumber2 = "12345678902";
$phoneNumber3 = "12345678903";
$templId = 7839;
```

- **单发短信**

```php
use Qcloud\Sms\SmsSingleSender;

try {
    $sender = new SmsSingleSender($appid, $appkey);
    $result = $sender->send(0, "86", $phoneNumber2,
        "测试短信，普通单发，深圳，小明，上学。", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
```

> `Note`: 如需发送海外短信，同样可以使用此接口，只需将国家码"86"改写成对应国家码号。

- **指定模板ID单发短信**

```php
use Qcloud\Sms\SmsSingleSender;

try {
    $sender = new SmsSingleSender($appid, $appkey);
    $params = ["指定模板单发", "深圳", "小明"];
    // 假设模板内容为：测试短信，{1}，{2}，{3}，上学。
    $result = $sender->sendWithParam("86", $phoneNumber2, $templId,
        $params, "", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
```

> `Note:`无论单发短信还是指定模板ID单发短信都需要从控制台中申请模板并且模板已经审核通过，才可能下发成功，否则返回失败。

- **群发**

```php
use Qcloud\Sms\SmsMultiSender;

try {
    $phoneNumbers = [$phoneNumber1, $phoneNumber2, $phoneNumber3];
    $sender = new SmsMultiSender($appid, $appkey);
    $result = $sender->send(0, "86", $phoneNumbers,
        "测试短信，普通群发，深圳，小明，上学。", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
```

- **指定模板ID群发**

```php
use Qcloud\Sms\SmsMultiSender;

try {
    $phoneNumbers = [$phoneNumber1, $phoneNumber2, $phoneNumber3];
    $sender = new SmsMultiSender($appid, $appkey);
    $params = ["指定模板群发", "深圳", "小明"];
    $result = $sender->sendWithParam("86", $phoneNumbers,
        $templId, $params, "", "", "");
    $rsp = json_decode($result);
    echo $result;
} catch(\Exception $e) {
    echo var_dump($e);
}
```

> `Note:`群发一次请求最多支持200个号码，如有对号码数量有特殊需求请联系腾讯云短信技术支持(QQ:3012203387)。

- **发送语音验证码**

```php
use Qcloud\Sms\SmsVoiceVerifyCodeSender;

try {
    $sender = new SmsVoiceVerifyCodeSender($appid, $appkey);
    $result = $sender->send("86", $phoneNumber1, "1234", 2, "");
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
```

> `Note`: 语音验证码发送只需提供验证码数字，例如在msg=“123”，您收到的语音通知为“您的语音验证码是1 2 3”，如需自定义内容，可以使用语音通知。

- **发送语音通知**

```php
use Qcloud\Sms\SmsVoicePromptSender;

try {
    $sender = new SmsVoicePromptSender($appid, $appkey);
    $result = $sender->send("86", $phoneNumber1, 2, "1234", "");
    $rsp = json_decode($result);
    echo $result;
} catch (\Exception $e) {
    echo var_dump($e);
}
```

- **拉取短信回执以及回复**

```php
use Qcloud\Sms\SmsStatusPuller;

try {
    $puller = new SmsStatusPuller($appid, $appkey);

    // 拉取短信回执
    $callbackResult = $puller->pullCallback(10);
    $callbackRsp = json_decode($callbackResult);
    echo $callbackResult;

    // 拉取回复
    $replyResult = $puller->pullReply(10);
    $replyRsp = json_decode($replyResult);
    echo $replyResult;
} catch (\Exception $e) {
    echo var_dump($e);
}
```

> `Note:` 短信拉取功能需要联系腾讯云短信技术支持(QQ:3012203387)，量大客户可以使用此功能批量拉取，其他客户不建议使用。

- **发送海外短信**

海外短信与国内短信发送类似
