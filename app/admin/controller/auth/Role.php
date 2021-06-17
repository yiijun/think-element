<?php
namespace app\admin\controller\auth;

use app\admin\controller\base\Base;

class Role extends Base {

    use \backend\traits\View;

    public function post(): \think\response\Json
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            $routes = [];
            foreach ($data['selected'] as  $value){
                foreach ($value as  $v) $routes[] = $v;
            }
            $data['routes'] = implode(',',array_unique($routes));
            $data['selected'] = json_encode($data['selected']);

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
}
