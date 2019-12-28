<?php
return [
    'verification'=>[
        'verify_type' =>[
            'register'=>['type_no' => 1,'interval'=> 600],
            ],
        'default'=>[
            'sms'=> Vency\Tools\Notification\SmsServiceApi\Ucpaas::class,
            'email'=> Vency\Tools\Notification\EmailServiceApi\Qmail::class,
        ],
        'smsapi'=>[
            'ucpaas'=>[
                'templateIdConfig'=>[
                                    'forget_pwd' => '484529',
                                    'forget_safe_pwd' => '484530',
                                    'register' => '484531',
                                    'withdraw' => '485263',
                                    'login' => '486087'
                ]
            ]
        ],
        'emailapi'=>[
            'Qmail'=>[
                'operationName' =>[
                    'forget_pwd' => '重置登录密码',
                    'forget_safe_pwd' => '重置安全密码',
                    'register' => '注册/绑定',
                    'withdraw' => '提现',
                    'login' => '登录'
                ]
            ]
        ]
    ]

];