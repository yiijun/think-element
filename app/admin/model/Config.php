<?php
namespace app\admin\model;

use think\Model;

class Config extends Model
{

    public function getConfigGroup()
    {
        return $this->where('key','group')->where('group','dictionary')->find();
    }

    public function getConfigByGroup($group)
    {
        return $this->where('group',$group)->select();
    }
}
