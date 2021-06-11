<?php
namespace app\admin\model\auth;
use think\Model;

class Menu extends Model
{
    protected $table = 'td_auth_menu';

    public function getShowRadio()
    {
        return [
            [
                'id' => '1',
                'name' => '否'
            ],
            [
                'id' => '2',
                'name' => '是'
            ]
        ];
    }

    public function getSonRadio()
    {
        return [
            [
                'id' => '1',
                'name' => '否'
            ],
            [
                'id' => '2',
                'name' => '是'
            ]

        ];
    }

    public function getPidSelect()
    {
        return $this->field('id,name')->where('pid',0)->select();
    }

    public function getShowAttr($value)
    {
        return [1 => '否',2 => '是'][$value];
    }

    public function getSonAttr($value)
    {
         return[0 => '未设置',1 => '否',2 => '是'][$value];
    }

}