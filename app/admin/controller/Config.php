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
                        'label' => $value['title'] . ' : {$site.' . $value['key'] . '}',
                        'value' => $value['value'],
                        'key' => $value['key'],
                        'placeholder' => '请输入' . $value['title'],
                        'prop' => [
                            'is_null' => true,
                            'bind_label'=> 'name',
                            'bind_value'=>'id',
                            'options' => json_decode($value['options'],true)??[]
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
            if($res){
                return success();
            }
            return  error('操作失败');
        }
    }
}
