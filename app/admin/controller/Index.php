<?php
namespace app\admin\controller;
use app\admin\controller\base\Base;
use think\facade\View;

class Index extends Base
{
    use \backend\traits\View;
    public function index()
    {
    		echo 333333333;
        	return View::fetch();
    }
}