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

    public $pk;

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
        $this->pk = $this->model->getPk();
        $this->rending = new Rendering(ucfirst($this->controller),$this->pk);
        \think\facade\View::assign('controller', $controller);
        \think\facade\View::assign('pk', $this->pk);
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
                if(!empty($list)){
                    $c = function (&$list) use (&$c){
                        foreach ($list as $key => &$value){
                            //根据父级别ID查询
                            $value['children'] = $this->model->where('pid',$value['id'])->select();
                            if(!empty($value['children'])){
                                $c($value['children']);
                            }
                        }
                        return $list;
                    };
                    $list = $c($list);
                }
            }else{
                $list = $this->model->limit($start, 20)->select();
            }

            $count = $this->model->count();
            return success([
                'list' => $list,
                'count' => intval($count)
            ]);
        }
        return \think\facade\View::fetch('common/index');
    }

    public function post(): \think\response\Json
    {
        if ($this->request->isPost()) {
            $data = Request::only($this->request->post());
            if(isset($data[$this->pk]) && !empty($data[$this->pk])){
                $res = $this->model::update($data,[$this->pk => $data[$this->pk]]);
            }else{
                $res = $this->model->save($data);
            }
            if (false !== $res) {
                return success([],200,'操作成功');
            }
            return  error('操作失败');
        }
    }

    public function delete(): \think\response\Json
    {
        if($this->request->isPost()){
            $id = $this->request->post($this->pk);
            if(empty($id) || !isset($id)) return error('缺失的主键');
            $res =  $this->model->where('id','=',$id)->delete();
            if($res){
                return success([],200,'删除数据成功');
            }
            return  error('删除数据失败');
        }
    }
}