<?php


namespace Vency\Tools\Notification;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class VerifyService
{
    protected array $options;

    /**
     * @var NotifyServiceInterface
     */
    protected $notifyService;

    public function __construct(NotifyServiceInterface $notifyService)
    {
        $this->notifyService = $notifyService;
    }
    
    /**
     * @param string $destination
     * @param string $verificationTypeName
     * @return array|null
     */
    //type : 1:忘记密码 forget_pwd 2安全密码 forget_safe_pwd 3.注册/绑定 register 4.提现 withdraw 5.登录 login
    public function getCode(string $destination,string $verificationTypeName): ?array{
        $str = "1234567890123456789012345678901234567890";
        $str = str_shuffle($str);
        $code = substr($str, 3, 6);
        $type = config("tools.verification.verify_type.{$verificationTypeName}.type_no");
        $content['code'] = $code;
        $content['type'] = $verificationTypeName;
        if(strpos($destination,'@')){
            $notifyServiceType = 'email';
        }else{
            $notifyServiceType = 'sms';
        }
        $codeInfo = ['phone' => $destination, 'type' => $type,'time' => time(),'code'=> $code];
        Db::table('sms')->insert($codeInfo);
        return $this->notifyService->sendContent($destination,$content,$notifyServiceType);
    }

    /**
     * @param string $destination
     * @param string $code
     * @param string $verificationTypeName
     * @return array|bool
     */
    static function  verifyCode( string $destination,string $code, string $verificationTypeName)
    {
        $result = 0;
        $message = '';
        $type = config("tools.verification.verify_type.{$verificationTypeName}.type_no");
        $interval = config("tools.verification.verify_type.{$verificationTypeName}.interval",600);
        $lastCodeInfo = Db::table('sms')->where(['phone' => $destination,'type' => $type])->select(['code','status','time'])
            ->orderBy('id','desc')->first();
        if( !$lastCodeInfo){
            //'请确认手机号码/邮箱地址！';
            $message = trans('messages.accountConfirmation');
        }elseif($lastCodeInfo->code != $code){
            //'验证码错误'
            $message = trans('messages.captchaErr');;
        } elseif ($lastCodeInfo->status==1 || ($lastCodeInfo->status==0 && $lastCodeInfo->time + 600 < time())){
            if($lastCodeInfo->status==0 && $lastCodeInfo->time + $interval < time()){
                Db::table('sms')->where(['status' => 0,'phone' => $destination,'code' => $code,'type' => $type])
                    ->update(['status' => 1]);
            }
            //'短信/邮箱验证码已过期！'
            $message = trans('messages.captchaExpired');
        }elseif($lastCodeInfo->status == 2){
            //'短信/邮箱验证码已使用！'
            $message = trans('messages.captchaConsumed');
        }else{
            $result = 1;
        }
        if($result == 0){
            return compact(['result','message']);
        }
        Db::table('sms')->where(['status' => 0,'phone' => $destination,'code' => $code,'type' => $type])
            ->update(['status' => 2]);
        return true;
    }

}