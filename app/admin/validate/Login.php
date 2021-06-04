<?php
declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class Login extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'uname'  =>  'require',
        'pass' =>  'require',
        'captcha|验证码'=>'require|captcha'
    ];

    protected $message = [
        'uname.require' => '用户名必须',
        'pass.require' => '密码必须',
    ];
}
