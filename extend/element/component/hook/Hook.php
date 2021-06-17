<?php
namespace element\component\hook;


class Hook
{
    public static function make($class,$fields,$form_name)
    {
       return call_user_func_array(['\\element\\component\\ui\\'.$class,'html'],[$fields,$form_name]);
    }
}