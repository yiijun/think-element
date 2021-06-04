<?php
namespace app\home\controller;
use think\annotation\Route;
/**
 * Class Index
 * @package app\home\controller
 */
class Index{

    /**
     * @param  string $name 数据名称
     * @return mixed
     * @Route("hello/:name", method="GET")
     */
    public function index()
    {
        return 'hello,';
    }
}