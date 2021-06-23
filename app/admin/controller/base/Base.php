<?php

namespace app\admin\controller\base;

use app\admin\model\Config;
use think\App;
use think\facade\View;

class Base
{
    protected $middleware = ['\\backend\\middleware\\Auth'];

    public function __construct()
    {
        //获取网站配置
        $configModel = new Config();
        $site = $configModel->getWebConfig();
        View::assign('site',$site);
    }
}
