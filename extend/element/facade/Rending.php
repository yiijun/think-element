<?php
namespace element\facade;

use think\Facade;

/**
 * Class Aside
 * @package element\facade
 * @method static string table_form_search_rules(array $fields, $tree_table = false, string $pk = 'id',$table = true,$search = true)
 * @method static string aside($routes)
 * @method static string breadcrumb($row,$mode)
 */
class Rending extends Facade
{
    protected static function getFacadeClass(): string
    {
        return 'element\\render\\Rending';
    }
}