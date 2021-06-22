<?php

namespace app\admin\controller\base;

use think\App;

class Base
{
    protected $middleware = ['\\backend\\middleware\\Auth'];

    public function __construct()
    {

    }
}
