<?php
namespace app\admin\controller\auth;


use think\facade\View;


class Admin
{
    /**
     * @return string
     */
    public function index()
    {
       return View::fetch();
    }
}
