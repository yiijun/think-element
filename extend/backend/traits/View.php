<?php

namespace backend\traits;

use element\Rendering;
use think\App;
use think\facade\Request;

trait View
{
    public $request;

    public $controller;

    public $model;

    public $rending;

    public function __construct(App $app)
    {
        $this->request = $app->request;
        $controller = $this->request->controller();

        //解析多级嵌套控制器
        $model_path = '\\app\\admin\\model\\';
        if (strstr($controller, '.')) {
            $model_name = explode('.', $controller);
            $this->controller = end($model_name);
            foreach ($model_name as $key => $value) {
                if ($key == count($model_name) - 1) break;
                $model_path .= $value . '\\';
            }
        }
        $model_path = $model_path . $this->controller;
        $this->model = new $model_path;
        $this->rending = new Rendering(ucfirst($this->controller));
        \think\facade\View::assign('controller', $controller);
    }

    public function index()
    {
        if ($this->request->isPost()) {
            //获取初始化数据
            $page = $this->request->post('page');
            $search = $this->request->post('search'); //根据字段信息拼接查询
            //todo 这里要实现查询
            $start = ($page - 1) * 20;

            //如果是树形表格,采用递归方式获取表格内容，并且默认上级字段为pid
            if(true === $this->rending->tree_table){
                $list = $this->model->where('pid',0)->limit($start, 20)->select();
            }

            $list = $this->model->limit($start, 20)->select();
            $count = $this->model->count();
            return success([
                'list' => $list,
                'count' => intval($count)
            ]);
        }
        return \think\facade\View::fetch('common/index');
    }

    public function post()
    {
        if ($this->request->isPost()) {
            $data = Request::only($this->request->post());
            if($data[$this->model->getPk()]){

            }
            $res = $this->model->save($data);
            if (false !== $res) {
                return success();
            }
            return  error('操作失败');
        }
    }

    public function del()
    {

    }
}