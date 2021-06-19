<?php
declare(strict_types = 1);
namespace app\admin\model;
use app\admin\model\auth\Role;
use think\Model;

class Admin extends Model
{
    public function rowByUname(string $uname)
    {
        return $this->where('uname',$uname)->findOrEmpty();
    }
}