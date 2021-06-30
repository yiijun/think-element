<?php
namespace app\admin\controller\general;

use app\admin\controller\base\Base;
use think\facade\View;

class Tool extends Base
{
    public function index()
    {
        return View::fetch();
    }
}