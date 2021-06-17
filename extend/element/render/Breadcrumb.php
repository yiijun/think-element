<?php
namespace element\render;
use think\facade\View;

class Breadcrumb
{
    public function render($row,$model)
    {
        $parents = function($pid) use ($model, &$parents) {
            static $data = [];
            if($pid != 0) $row = $model->getRowById($pid);
            if(!empty($row)) {
                $data[] = $row;
                $parents($row['pid']);
            }
            return $data;
        };
        $data = isset($row['pid']) ? $parents($row['pid']) : [];
        array_unshift($data, $row);
        $breadcrumb_html = '';
        foreach (array_reverse($data) as $key => $value) {
            $breadcrumb_html .= isset($value['route']) ? ' <el-breadcrumb-item><a href="' . $value['route'] . '">' . $value['name'] . '</a></el-breadcrumb-item>' : '';
        }
        View::assign('breadcrumb_html',$breadcrumb_html);
    }
}
