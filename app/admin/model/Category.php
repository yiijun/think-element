<?php
namespace app\admin\model;

use think\Model;

class Category extends Model
{
    public function getPidSelect() : array
    {
        $menu =  $this->field('id,name,pid')->select();
        return tree($menu,0);
    }
}