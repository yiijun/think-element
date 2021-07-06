<?php

namespace app\admin\controller\general;

use app\admin\controller\base\Base;
use app\admin\model\auth\Menu;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

/**
 * Class Tool
 * @package app\admin\controller\general
 * 作为通用模块的生成工具
 * 主要用于更加快捷的生成控制器、配置文件和模型
 */
class Tool extends Base
{
    public function index()
    {
        return View::fetch();
    }

    public function post()
    {
        if (Request::isPost()) {
            $data = Request::post();
            if (empty($data['fields'])) return error('字段是生成数据库表对的依据、必须配置');

            $controllerName = Request::post('controller_name');

            $controllerArr = explode('.', $controllerName);

            $name = ucfirst(end($controllerArr)); //控制器、模型、表名称

            $controllerPath = root_path() . 'app/admin/controller/';

            $modelPath = root_path() . 'app/admin/model/';

            $jsonPath = root_path() . 'public/static/backend/json/';

            $controllerNamespace = "app\admin\controller";

            $modelNamespace = "app\admin\model";

            if (count($controllerArr) > 1) {
                array_pop($controllerArr);
                foreach ($controllerArr as $value) {
                    $controllerPath .= $value . '/';
                    $modelPath .= $value . '/';
                    $jsonPath .= $value . '/';
                    $controllerNamespace .= '\\' . $value;
                    $modelNamespace .= '\\' . $value;
                }
                directory($controllerPath);//创建控制器文件夹
                directory($modelPath);
                directory($jsonPath); //创建配置文件文件夹
            }

            //创建控制器
            $controllerTpl = file_get_contents(root_path() . 'extend/backend/tpl/controller.tpl');

            if (!$controllerTpl) {
                return error('读区控制器模版失败');
            }
            $ext = '.php';

            $controllerRet = file_put_contents(rtrim($controllerPath, '/') . '/' . $name . $ext, sprintf($controllerTpl, $controllerNamespace, $name));

            if (!$controllerRet) {
                return error('控制器创建失败，请检查文件夹权限');
            }

            //创建模型
            $modelTpl = file_get_contents(root_path() . 'extend/backend/tpl/model.tpl');

            if (!$modelTpl) {
                return error('读区模型模版失败');
            }

            $modelRet = file_put_contents(rtrim($modelPath, '/') . '/' . $name . $ext, sprintf($modelTpl, $modelNamespace, $name));

            if (!$modelRet) {
                return error('创建模型失败，请检查文件夹权限');
            }

            //创建数据表
            $databaseConfig = Config::get('database');

            $prefix = $databaseConfig['connections']['mysql']['prefix'];
            $charset = $databaseConfig['connections']['mysql']['charset'];

            $tableName = strtolower($prefix.$name);
            $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}`(";
            $sql .= "`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',";

            foreach ($data['fields'] as &$field) {
                if(!$field['prop']['callback'][0] || !$field['prop']['callback'][1] ){
                    unset($field['prop']['callback']);
                }

                if(!$field['prop']['ext']){
                    unset($field['prop']['ext']);
                }

                if(!$field['prop']['options']){
                    unset($field['prop']['bind_label']);
                    unset($field['prop']['bind_value']);
                    unset($field['prop']['options']);
                }

                switch (strtolower($field['type'])) {
                    case 'input':
                    case 'textarea':
                    case 'password':
                    case 'image':
                    case 'cascaderCheckBox':
                        $sql .= "`{$field['key']}` varchar(255) NOT NULL  DEFAULT '' COMMENT '{$field['label']}',";
                        break;
                    case 'content':
                        $sql .= "`{$field['key']}` txt  NULL   COMMENT '{$field['label']}',";
                        break;
                    case 'number':
                        $sql .= "`{$field['key']}` int(11) NOT NULL  DEFAULT 0 COMMENT '{$field['label']}',";
                        break;
                    case 'radio':
                    case 'select':
                    case 'cascaderRadio':
                        if (!$field['value']) {
                            return error('为选项字段时必须填写一个默认值，默认值为选项中的一个');
                        }
                        $sql .= "`{$field['key']}` tinyint(4) NOT NULL  DEFAULT {$field['value']} COMMENT '{$field['label']}',";
                        break;
                    case 'Markdown':
                        $sql .= "`{$field['key']}` text NULL  COMMENT '{$field['label']}'";
                        break;
                }
            }
            $sql .= "`create_time` datetime DEFAULT NULL  COMMENT '创建时间',";
            $sql .= "  PRIMARY KEY (`id`) USING BTREE) ";
            $sql .= " ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET={$charset};";

            //生成菜单
            if(true === $data['route']){
                try {
                    $menuModel = new Menu();
                    $menu_id = $menuModel->insertGetId([
                        'name' => $data['menu_name'],
                        'pid' => 0,
                        'icon' => 'el-icon-tickets',
                        'route' => '/admin/'.$data['controller_name'].'/index',
                        'weigh' => 0,
                        'show' => 2,
                        'son' => 1,
                        'create_time' => date('Y-m-d H:i:s')
                    ]);
                    if($menu_id){
                        $auths = ['post' => '添加\修改','delete' => '删除单行','deletes' => '批量删除'];
                        foreach ($auths as $key => $auth){
                            $menuModel->insert(
                                [
                                    'name' => $auth,
                                    'pid' => $menu_id,
                                    'icon' => 'el-icon-tickets',
                                    'route' => '/admin/'.$data['controller_name'].'/'.$key,
                                    'weigh' => 0,
                                    'show' => 1,
                                    'son' => 2,
                                    'create_time' => date('Y-m-d H:i:s')
                                ]
                            );
                        }
                    }
                }catch (\Exception $exception){
                    return  error('生成菜单失败');
                }
            }

            //创建配置文件
            $json = json_encode($data, 256);

            $jsonRet = file_put_contents(rtrim($jsonPath, '/') . '/' . strtolower($name) . '.json', $json);

            if (!$jsonRet) {
                return error('创建配置文件失败，请检查文件夹权限');
            }

            //执行sql语句
            Db::connect()->execute($sql);
            return success([
                'url' => url('admin/'.$data['controller_name'])
            ],200,'生成模块成功');
        }
    }
}