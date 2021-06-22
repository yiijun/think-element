<?php
namespace app\admin\model\auth;
use think\Model;

class Admin extends Model
{
    protected $name = 'admin';

    public function rowByUname(string $uname)
    {
        return $this->where('uname',$uname)->findOrEmpty();
    }
}