<?php
namespace backend\traits;

use element\Rendering;
use think\App;

trait View
{
    public $request;

    public $controller;

    public function __construct(App $app)
    {
        $this->request = $app->request;
        $this->controller = $this->request->controller();
        if(strstr($this->controller,'.')){
            $model_name = explode('.',$this->controller);
            $this->controller = end($model_name);
        }
        new Rendering(ucfirst($this->controller));
    }

    public function index() : string
    {
        return \think\facade\View::fetch('common/index');
    }

    public function post()
    {

    }

    public function del()
    {

    }
}