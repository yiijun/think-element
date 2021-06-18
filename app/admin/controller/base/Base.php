<?php

namespace app\admin\controller\base;

use element\facade\Rending;
use think\App;
use app\admin\model\auth\Menu;
use element\facade\Aside;
use element\facade\Breadcrumb;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Base
{
    public $controller;

    public $action;

    public $pk = 'id';

    public $model;

    public $is_render = true;

    public function __construct()
    {
        $login_info = Session::get('login_info');
        $this->controller = Request::controller();
        $this->action = Request::action();
        $modelMenu = new Menu();
        $current_menu = '/admin/' . strtolower($this->controller) . '/' . strtolower($this->action);
        $current_menu = $modelMenu->getRowByRoute($current_menu);
        Rending::aside();
        Rending::breadcrumb($current_menu,$modelMenu);
        View::assign([
            'controller' => $this->controller,
            'pk' => $this->pk,
            'current_route_id' => $current_menu['id'] ?? 1
        ]);
    }
}
