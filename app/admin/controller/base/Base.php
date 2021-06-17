<?php
namespace app\admin\controller\base;

use app\admin\model\auth\Menu;
use element\Rendering;
use think\App;
use think\facade\Request;
use think\facade\View;

class Base
{
    public $controller;

    public $rending;

    public $action;

    public $pk;

    public $model;

    public function __construct()
    {
        $this->controller = Request::controller();
        $this->action = Request::action();
        $current_menu = '/admin/'.strtolower($this->controller).'/'.strtolower($this->action);
        $model = new Menu();
        $current_menu = $model->getRowByRoute($current_menu);
        $model_name = explode('.', $this->controller);
        $end = end($model_name);
        $model_path = '\\app\\admin\\model\\';
        $model_name = explode('.', $this->controller);
        foreach ($model_name as $key => $value) {
            if ($key == count($model_name) - 1) break;
            $model_path .= $value . '\\';
        }
        $model_path = $model_path . $end;
        $this->model = new $model_path;
        $this->pk = $this->model->getPk();
        $this->rending = new Rendering(ucfirst($end), $this->pk);
        $this->rending->aside();
        $this->rending->breadcrumb($current_menu,$model);
        View::assign([
            'controller' => $this->controller,
            'pk' => $this->pk,
            'current_route_id' => $current_menu['id']
        ]);
    }
}
