<?php

namespace app\admin\controller;

use app\admin\controller\base\Base;
use think\App;
use think\facade\View;

class Index extends Base
{
    public function index(App $app) : string
    {
        return View::fetch();
    }

    public function jurisdiction() : string
    {
        return View::fetch();
    }
}