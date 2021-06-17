<?php
namespace app\admin\model\auth;
use think\Model;

class Role extends Model
{
    protected $name = 'auth_role';

    protected $pk = 'aid';

    public function getSelectedAttr($value,$data)
    {
        return json_decode($data['selected'],true);
    }

    public function getRoleAll()
    {
        return $this->field('id,name')->select();
    }
}