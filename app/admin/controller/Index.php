<?php
namespace app\admin\controller;
use think\facade\View;

class Index
{
    public function index()
    {
    		echo 333333333;
        	return View::fetch();
    }
}