<?php
namespace element\render;
use app\admin\model\auth\Menu;
use think\facade\View;

/**
 * Class Aside
 * @package element\render
 * 渲染无限极菜单
 */
class Aside
{
    /**
     * 菜单
     */
    public function render()
    {
        $model = new Menu();
        View::assign('aside_html',$this->treeAside(
            tree($model->getMenus(),0)
        ));
    }

    /**
     * @param array $menus
     * @return string
     * 递归菜单
     */
    private function treeAside(array $menus) : string
    {
        $html = '';
        if(is_array($menus)) {
            foreach ($menus as $row) {
                if(empty($row['children'])) {
                    $html .= '<a href="' . $row['route'] . '"><el-menu-item index="' . $row['id'] . '"><template #title><i class="' . $row['icon'] . '"></i><span>' . $row['name'] . '</span></template></el-menu-item></a>';
                } else {
                    $html .= '<el-submenu index="' . $row['id'] . '"><template #title><i class="' . $row['icon'] . '"></i> <span>' . $row['name'] . '</span></template>';
                    $html .= $this->treeAside($row['children']);
                    $html .= '</el-submenu>';
                }
            }
        }
        return $html;
    }

}