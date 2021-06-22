<?php
namespace app\admin\model\auth;
use think\Model;

class Menu extends Model
{
    protected $name = 'auth_menu';

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

    public function getMenus($roles = '')
    {
        if(empty($roles)){
            return $this
                ->field('id,name,pid,icon,route')
                ->where('show',2)
                ->order('weigh','asc')
                ->select();
        }else{
            return $this
                ->field('id,name,pid,icon,route')
                ->where('show',2)
                ->where('id','in',$roles)
                ->order('weigh','asc')
                ->select();
        }

    }

    public function getRowByRoute(string  $route = '')
    {
        return $this->where("route","like",'%'.$route.'%')->findOrEmpty();
    }

    public function getRowById($id)
    {
        return $this->where("id",$id)->findOrEmpty();
    }
}