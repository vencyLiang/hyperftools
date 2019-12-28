<?php
namespace Vency\Tools\Notification\SmsServiceApi;
use Hyperf\Di\Annotation\Inject;
use GuzzleHttp\Client;
class Ucpaas
{   private
    //API请求地址
    const BaseUrl = "https://open.ucpaas.com/ol/sms/";

    //开发者账号ID。由32个英文字母和阿拉伯数字组成的开发者账号唯一标识符。
    private $accountSid='e9b370555f53aa7fa83c6d1547ecb1a2';

    //开发者账号TOKEN
    private $token='36d2130718bb7d0717514a133c9d0cd6';

    private $appId = 'f10c70b1b0de47cdb82993fe9a2b5fed';

    private $client;


    public function  __construct($options=[])
    {
        $this->client = new Client(['base_uri'=> self::BaseUrl]);
        if (is_array($options) && !empty($options)) {
            $this->accountSid = isset($options['accountsid']) ? $options['accountsid'] : '';
            $this->token = isset($options['token']) ? $options['token'] : '';
            $this->appId = isset($options['appId']) ? $options['appId'] : '';
        }
    }

    public function send($mobile,$content){
        //type : 1:忘记密码 forget_pwd 2安全密码 forget_safe_pwd 3.注册/绑定 register 4.提现 withdraw 5.登录 login
        //$templateArr = [ 1 => '484529', 2 => '484530',3 => '484531',4 => '485263',5 => '486087'];
        $templateIdConfig = config("tools.verification.smsapi.ucpaas.templateIdConfig");
        $body = [
            'sid' => $this->accountSid,
            'token' => $this->token,
            'appid'=> $this->appId,
            'templateid'=> $templateIdConfig[$content['type']],
            'param'=> $content['code'],
            'mobile'=> $mobile,
            'uid'=>'',
        ];
        $res = $this->client->post('sendsms',[
            'headers'=>[
                'Accept' => 'application/json',
                //'Content-Type' => 'application/json;charset=utf-8',
            ],
            'json' => $body
        ]);
       return json_decode((string) $res->getBody(),true);
    }

} 