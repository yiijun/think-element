<?php

namespace app\admin\controller\auth;

use app\admin\controller\base\Base;
use think\facade\Request;


class Admin extends Base
{
    use \backend\traits\View;

    public function post(): \think\response\Json
    {
        if (Request::isPost()) {
            $data = Request::post();
            $data['pass'] = password_hash($data['pass'],1);
            $data['ip'] = $_SERVER['REMOTE_ADDR'];
            if (isset($data[$this->pk]) && !empty($data[$this->pk])) {
                $res = $this->model::update($data, [$this->pk => $data[$this->pk]]);
            } else {
                $res = $this->model->save($data);
            }
            if (false !== $res) {
                return success([], 200, '操作成功');
            }
            return error('操作失败');
        }
    }
}
