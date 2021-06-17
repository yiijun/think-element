<?php
namespace element\facade;

use think\Facade;

class Breadcrumb extends Facade
{
    protected static function getFacadeClass()
    {
        return 'element\\render\\Breadcrumb';
    }
}