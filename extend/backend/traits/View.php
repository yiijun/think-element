<?php

namespace backend\traits;

use element\Rendering;
use think\App;
use think\response\Json;

trait View
{
    public $request;

    public $controller;

    public $model;

    public $rending;

    public function __construct(App $app)
    {
        $this->request = $app->request;
        $this->controller = $this->request->controller();
        $model_path = '\\app\\admin\\model\\';
        if (strstr($this->controller, '.')) {
            $model_name = explode('.', $this->controller);
            $this->controller = end($model_name);
            foreach ($model_name as $key => $value) {
                if ($key == count($model_name) - 1) break;
                $model_path .= $value . '\\';
            }
        }
        $model_path = $model_path . $this->controller;
        $this->model = new $model_path;
        $rendering = new Rendering(ucfirst($this->controller));
    }

    public function index()
    {
        if ($this->request->isPost()) {
            //获取初始化数据
            $page = $this->request->post('page');
            $search = $this->request->post('search'); //根据字段信息拼接查询
            $start = ($page - 1) * 20;
            $list = $this->model->limit($start, 20)->select();
            return success([
                'list' => $list
            ]);
        }
        return \think\facade\View::fetch('common/index');
    }

    public function post()
    {

    }

    public function del()
    {

    }
}