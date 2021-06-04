<?php
namespace app\admin\controller;
use think\facade\View;

class Index
{
    public function index()
    {
        return View::fetch();
    }
}