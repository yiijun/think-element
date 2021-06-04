<?php
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\Admin;
use think\exception\ValidateException;
use think\facade\Session;
use think\facade\View;
use think\facade\Request;
use think\response\Json;

class Login
{
    public function index(): string
    {
        return View::fetch();
    }

    public function post(): Json
    {
        if (Request::isPost()) {
            $uname = Request::post('uname');
            $pass = Request::post('pass');
            $captcha = Request::post('captcha');
            try {
                validate(\app\admin\validate\Login::class)->check([
                    'uname' => $uname,
                    'pass' => $pass,
                    'captcha' => $captcha
                ]);
            } catch (ValidateException $e) {
                return error($e->getError(), $e->getCode());
            }
            $adminModel = new Admin();
            $admin = $adminModel->rowByUname($uname);
            if (!$admin) return error('不存在的用户');
            if (!password_verify($pass, $admin['pass'])) return error('密码不正确');
            Session::set('user_info',[
                'aid' => $admin['aid'],
                'uname' => $admin['uname']
            ]);
            return success([],200,'登陆成功');
        }
    }
}
