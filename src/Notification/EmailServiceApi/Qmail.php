<?php


namespace Vency\Tools\Notification\EmailServiceApi;
use Vency\Tools\Notification\EmailServiceApi\Qmail\{SMTP,PHPMailer,Exception};


class Qmail
{
    private $config = [
                        "sendmail" => "kenpia9731@qq.com",
                        "sendmailpwd" => "wnfsrrbtbtwbdchb",
                        "send_name" => "麦信钱包",
                        "Charset" => "utf8",
                        "Host" =>  "smtp.qq.com",
                        "SMTPAuth" => true,
                        "SMTPSecure" => "ssl",
                        "Port" => 465,
                        "to_name" => "",

    ];

    public function __construct($config=[])
    {
        if($config){
            $this->config = $config;
        }

    }

    public function send($address,$content){
        $operationNameConfig = config("tools.verification.emailapi.Qmail.operationName");
        $sign = $operationNameConfig[$content['type']];
        $sendmail = $this->config['sendmail']; //发件人邮箱
        $sendmailpswd = $this->config['sendmailpwd']; //客户端授权密码,而不是邮箱的登录密码，就是手机发送短信之后弹出来的一长串的密码
        $send_name = $this->config['send_name'];// 设置发件人信息，如邮件格式说明中的发件人，
        $toemail = $address;//定义收件人的邮箱
        $to_name = $this->config['to_name'];//设置收件人信息，如邮件格式说明中的收件人
        $mail = new PHPMailer();
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = $this->config['Charset'];// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = $this->config['Host'];// 发送方的SMTP服务器地址
        $mail->SMTPAuth = $this->config['SMTPAuth'];// 是否使用身份验证
        $mail->Username = $sendmail;//// 发送方的
        $mail->Password = $sendmailpswd;//客户端授权密码,而不是邮箱的登录密码！
        $mail->SMTPSecure = $this->config['SMTPSecure'];// 使用ssl协议方式
        $mail->Port = $this->config['Port'];//  qq端口465或587）
        $mail->setFrom($sendmail, $send_name);// 设置发件人信息，如邮件格式说明中的发件人，
        $mail->addAddress($toemail, $to_name);// 设置收件人信息，如邮件格式说明中的收件人，
        $mail->addReplyTo($sendmail, $send_name);// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        $mail->Subject = "【麦信钱包】重要系统邮件";// 邮件标题
        $mail->Body = "您正在进行{$sign}操作,验证码是：".$content['code']."，如果非本人操作无需理会！";// 邮件正文
        if (!$mail->send()) { // 发送邮件
            return ['result' => 0 , 'data' => null , 'message' => $mail->ErrorInfo];
        }else{
            //发送邮箱验证码成功；
            return ['result' => 1, 'data' => null , 'message' => trans("app.config.email.feedback")];
        }

    }
}