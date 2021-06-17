<?php

namespace backend\fields;

class Role
{
    const FORM_FIELD = [
        [
            'type' => 'input',
            'label' => '角色名称',
            'key' => 'name',
            'value' => '',
            'placeholder' => '请输入菜单名称',
            'prop' => [
                'table_show' => true, //table 是否显示列
                'search' => 'like', //是否查询
                'is_null' => true,//是否必填
                'trigger' => 'blur'
            ]
        ],
        [
            'type' => 'input',
            'label' => '角色描述',
            'key' => 'desc',
            'value' => '',
            'placeholder' => '请输入菜单名称',
            'prop' => [
                'table_show' => true, //table 是否显示列
                'search' => 'like',   //是否查询
                'is_null' => true,    //是否必填
                'trigger' => 'blur'
            ]
        ],
        [
            'type' => 'CascaderCheckBox',
            'label' => '选择权限',
            'key' => 'selected',
            'value' => [],
            'placeholder' => '选择父级菜单',
            'prop' => [
                'table_show' => false,
                'is_null' => false,
                'trigger' => 'change',
                'filterable' => true,
                'emitPath' => 'true',
                'callback' => ['\\app\\admin\\model\\auth\\Menu', 'getPidSelect','id','name'],
            ]
        ],
    ];
}
