<?php

namespace backend\fields;

class Admin
{
    const FORM_FIELD = [
        [
            'type' => 'Input',
            'label' => '用户名',
            'key' => 'uname',
            'value' => '',
            'placeholder' => '请输入用户名',
            'prop' => [
                'table_show' => true, //table 是否显示列
                'search' => 'like', //是否查询
                'is_null' => true,//是否必填
                'trigger' => 'blur'
            ]
        ],
        [
            'type' => 'Input',
            'label' => '手机号',
            'key' => 'mobile',
            'value' => '',
            'placeholder' => '请输入手机号',
            'prop' => [
                'table_show' => true,
                'search' => '=',
                'is_null' => true,
                'trigger' => 'blur'
            ]
        ],
        [
            'type' => 'Password',
            'label' => '密码',
            'key' => 'pass',
            'value' => '',
            'placeholder' => '请输入密码',
            'prop' => [
                'table_show' => false, //table 是否显示列
                'is_null' => true,    //是否必填
                'trigger' => 'blur'
            ]
        ],
        [
            'type' => 'select',
            'label' => '所属角色',
            'key' => 'rid',
            'value' => [],
            'placeholder' => '选择角色',
            'prop' => [
                'table_show' => true,
                'is_null' => true,
                'trigger' => 'change',
                'filterable' => true,
                'emitPath' => 'true',
                'callback' => ['\\app\\admin\\model\\auth\\Role', 'getRoleAll','id','name'],
            ]
        ],
    ];
}
