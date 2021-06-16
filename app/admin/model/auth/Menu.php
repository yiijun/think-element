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
                'sid' => 1,
                'name' => '否'
            ],
            [
                'sid' => 2,
                'name' => '是'
            ]
        ];
    }

    public function getSonRadio()
    {
        return [
            [
                'sid' => 1,
                'name' => '否'
            ],
            [
                'sid' => 2,
                'name' => '是'
            ]

        ];
    }

    public function getPidSelect() : array
    {
        $menu =  $this->field('id,name,pid')->select();
        return tree($menu,0);
    }
}