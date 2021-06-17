<?php
namespace app\admin\controller;
use app\admin\controller\base\Base;
use think\facade\View;

class Index extends Base
{
    public $is_render = false;
    public function index()
    {
        return View::fetch();
    }
}