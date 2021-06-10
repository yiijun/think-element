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
                'name' => '是'
            ],
            [
                'id' => '2',
                'name' => '否'
            ]

        ];
    }

    public function getSonRadio()
    {
        return [
            [
                'id' => '1',
                'name' => '是'
            ],
            [
                'id' => '2',
                'name' => '否'
            ]

        ];
    }

    public function getPidSelect()
    {
        return $this->field('id,name')->where('pid',0)->select();
    }

}