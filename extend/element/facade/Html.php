<?php
namespace element\facade;

use think\Facade;

class Html extends Facade
{
    protected static function getFacadeClass()
    {
        return 'element\\render\\Html';
    }
}