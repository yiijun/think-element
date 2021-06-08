<?php

namespace backend\fields;

/**
 * Class Menu
 * @package extend\backend\fields
 * 定义菜单字段，渲染Form
 */
class Menu
{

    const FORM_FIELD = [
        [
            'type' => 'input',
            'label' => '菜单名称',
            'key' => 'name',
            'value' => '',
            'placeholder' => '请输入菜单名称',
            'prop' => [
                'table_show' => true, //table 是否显示列
                'search' => 'like' //是否查询
            ]
        ],
        [
            'type' => 'select',
            'label' => '父级菜单',
            'key' => 'pid',
            'value' => '',
            'placeholder' => '选择父级菜单',
            'prop' => [
                'table_show' => true,
                'search' => 'like',
                'callback' => ['menu', 'getParent'],
                'ext' => [
                    'filterable' => true,
                    'label' => 'name',
                    'value' => 'id',
                ]
            ]
        ],
        [
            'type' => 'input',
            'label' => 'Icon',
            'key' => 'icon',
            'value' => '',
            'placeholder' => '请输入菜单图标',
            'prop' => [
                'table_show' => true,
                'search' => 'like'
            ]
        ],
        [
            'type' => 'input',
            'label' => '路由',
            'key' => 'route',
            'value' => '',
            'placeholder' => '请输入路由规则',
            'prop' => [
                'table_show' => true,
                'search' => 'like'
            ]
        ],
        [
            'type' => 'number',
            'label' => '权重',
            'key' => 'weigh',
            'value' => '',
            'prop' => [
                'table_show' => true,
                'search' => 'like',
                'ext' => [
                    'min' => 0,
                    'max' => 9999999,
                    'step' => 1,
                ]
            ]
        ],
        [
            'type' => 'radio',
            'label' => '显示',
            'key' => 'show',
            'value' => '',
            'prop' => [
                'table_show' => true,
                'search' => 'like',
                'callback' => ['menu', 'getShowRadio'],
                'ext' => [
                    'label' => 'name',
                    'value' => 'id',
                ]
            ]
        ],
        [
            'type' => 'radio',
            'label' => '子菜单',
            'key' => 'son',
            'value' => '',
            'prop' => [
                'table_show' => true,
                'search' => 'like',
                'callback' => ['menu', 'getSonRadio'],
                'ext' => [
                    'label' => 'name',
                    'value' => 'id',
                ]
            ]
        ],
    ];
}
