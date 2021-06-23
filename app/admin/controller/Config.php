<?php

namespace app\admin\controller;

use app\admin\controller\base\Base;
use element\facade\Rending;
use think\facade\Request;
use think\facade\View;

class Config extends Base
{
    public function index()
    {
        $configModel = new \app\admin\model\Config();
        $active_name = Request::get('active_name');
        $group = $configModel->getConfigGroup();
        $group['value'] = json_decode($group['value'], true);
        $config = $configModel->getConfigByGroup($active_name);
        $fields['fields'] = [];
        $fields['tree_table'] = false;
        $fields['expand_all'] = false;
        foreach ($config as $key => &$value) {
            switch ($value['type']) {
                case 'json':
                    $fields_config = json_decode($value['value'], true);
                    foreach ($fields_config as $f => $field) {
                        $fields['fields'][] = [
                            'type' => 'input',
                            'key' => $f,
                            'label' => $f,
                            'value' => $field,
                            'placeholder' => '请输入' . $field,
                            'prop' => [
                                'is_null' => true
                            ]
                        ];
                    }
                    break;
                default:
                    $fields['fields'][] = [
                        'type' => $value['type'],
                        'label' => $value['title'],
                        'value' => $value['value'],
                        'key' => $value['key'],
                        'placeholder' => '请输入' . $value['title'],
                        'prop' => [
                            'is_null' => false,
                            'bind_label' => 'name',
                            'bind_value' => 'id',
                            'options' => json_decode($value['options'], true) ?? []
                        ]
                    ];
            }
        }
        View::assign('active_name', $active_name);
        Rending::table_form_search_rules($fields, false, 'id', false, false);
        if (Request::isPost()) {
            $configModel = new \app\admin\model\Config();
            $group = $configModel->getConfigGroup();
            $group['value'] = json_decode($group['value']);
            return success([
                'group' => $group,
            ]);
        }
        return View::fetch();
    }

    public function post()
    {
        if (Request::isPost()) {
            $data = Request::post();
            $configModel = new \app\admin\model\Config();
            $res = $configModel->save($data);
            if ($res) {
                return success([], 200, '添加配置成功');
            }
            return error('操作失败');
        }
    }

    public function save()
    {
        if (Request::isPost()) {
            $data = Request::post('data');
            $active_name = Request::post('active_name');
            $configModel = new \app\admin\model\Config();
            if($active_name == 'dictionary'){
                $configModel::update(['value' => json_encode($data)],['group' => $active_name]);
            }else{
                foreach ($data as $key => $value) {
                    $configModel::update(['value' => $value], ['key' => $key, 'group' => $active_name]);
                }
            }
            return success([], 200, '保存成功');
        }
        return error('操作失败');
    }

    public function group()
    {
        if (Request::isPost()) {
            $data = Request::post();
            $data['group']['value'][$data['data']['key']] = $data['data']['value'];
            $data['group']['value'] = json_encode($data['group']['value'], 256);
            $configModel = new \app\admin\model\Config();
            $res = $configModel::update($data['group'], ['id' => $data['group']['id']]);
            if ($res) {
                return success([], 200, '增加分组成功');
            }
            return error('操作失败');
        }
    }

    public function delete()
    {
        if(Request::isPost()) {
            $data = Request::post();
            $configModel = new \app\admin\model\Config();
            if($data['active_name'] == 'dictionary'){
                $group = $configModel->getConfigGroup();
                $group['value'] = json_decode($group['value'], true);
                $group = $group->toArray();
                unset($group['value'][$data['key']]);
                try{
                    $res1 = $configModel::update([
                        'value' => json_encode($group['value'],256)
                    ],['id' => $group['id']]);
                    $res2 = $configModel->where(['group' => $data['key']])->delete();
                    $res = false;
                    if($res1 && $res2){
                        $res = true;
                        $configModel->commit();
                    }
                }catch (\Exception $exception){
                    $configModel->rollback();
                    return error('操作失败');
                }
            }else{
                $res = $configModel->where(['group' => $data['active_name'],'key' => $data['key']])->delete();
            }
            if ($res) {
                return success([], 200, '删除配置成功');
            }
            return error('操作失败');
        }
    }
}
