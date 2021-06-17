<?php
namespace element\facade;

use think\Facade;

class Aside extends Facade
{
    protected static function getFacadeClass()
    {
        return 'element\\render\\Aside';
    }
}