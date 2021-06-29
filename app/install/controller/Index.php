<?php

namespace app\install\controller;

use think\db\exception\PDOException;
use think\facade\Config;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Index
{
    public function index()
    {
        if (file_exists(root_path().'pubic/install.lock')) {
            $this->error('网站已经安装', cmf_get_root() . '/');
        }

        return View::fetch();
    }

    public function step1()
    {
        if (Request::isPost()) {
            $env[] = [
                'name' => '操作系统',
                'record' => 'Unix',
                'current' => PHP_OS,
                'low' => '不限制',
                'status' => true
            ];
            $env[] = [
                'name' => 'PHP版本',
                'record' => '>7.0.x',
                'current' => PHP_VERSION,
                'low' => '7.0.x',
                'status' => PHP_VERSION > '7.0'
            ];

            $other = [
                'session',
                'pdo',
                'pdo_mysql',
                'curl',
                'gd',
                'mbstring',
                'fileinfo'
            ];
            foreach ($other as $value) {
                $open = extension_loaded($value);
                $ext[] = [
                    'name' => $value,
                    'record' => '开启',
                    'current' => $open ? '支持' : '不支持',
                    'low' => '开启',
                    'status' => $open
                ];
            }
            $check_path = [
                [
                    'name' => '控制器',
                    'path' => 'app/admin/controller'
                ],
                [
                    'name' => '模型',
                    'path' => 'app/admin/model'
                ],
                [
                    'name' => '视图',
                    'path' => 'app/admin/view'
                ],
                [
                    'name' => '文件上传',
                    'path' => 'public/storage'
                ],
                [
                    'name' => '配置文件',
                    'path' => 'public/static/backend/json'
                ],
            ];
            $paths = [];

            foreach ($check_path as $value) {
                $path = [
                    'name' => $value['name'],
                    'record' => '可写',
                    'current' => '可写',
                    'low' => '可写',
                    'status' => true,
                    'path' => root_path() . $value['path']
                ];
                if (!is_writable(root_path() . $value['path'])) {
                    $path['current'] = '不可写';
                    $path['status'] = false;
                }
                $paths[] = $path;
            }
            $all = $env + $ext + $paths;
            $is_next = true;
            foreach ($all as $value) {
                if (false === $value['status']) {
                    $is_next = false;
                    break;
                }
            }
            return success([
                'env' => $env,
                'ext' => $ext,
                'paths' => $paths,
                'is_next' => $is_next
            ]);
        }
    }

    public function check()
    {
        if (Request::isPost()) {
            $db_config = Config::get('database');
            $db_config['connections']['dynamic'] = [
                'type' => 'mysql',
                'hostname' => Request::post('hostname'),
                'username' => Request::post('username'),
                'password' => Request::post('password'),
                'hostport' => Request::post('hostport'),
                'charset' => Request::post('charset'),
            ];
            Config::set($db_config, 'database');
            try {
                Db::connect('dynamic', true)->connect();
            } catch (\PDOException $exception) {
                return error($exception->getMessage(), $exception->getCode(), ['next' => false]);
            }
            if ('check' == Request::post('action')) {
                return success(['next' => true], 200, '链接数据库成功');
            }
            //创建数据库
            $sql = "CREATE DATABASE IF NOT EXISTS `" . Request::post('database') . "` DEFAULT CHARACTER SET " . Request::post('charset');

            try {
                Db::connect('dynamic', false)->execute($sql);
            } catch (\PDOException $exception) {
                return error($exception->getMessage(), $exception->getCode());
            }

            Session::set('install_databases', [
                'type' => 'mysql',
                'hostname' => Request::post('hostname'),
                'username' => Request::post('username'),
                'password' => Request::post('password'),
                'hostport' => Request::post('hostport'),
                'database' => Request::post('database'),
                'charset' => Request::post('charset'),
                'prefix' => Request::post('prefix')
            ]);

            //读区sql 文件
            $sql_path = app_path() . 'data/install.sql';
            $sql = split_sql($sql_path, Request::post('prefix'), Request::post('charset'));
            Session::set('install_sql', $sql);
            $sql_count = count($sql);

            //设置站点名称
            Session::set('install_site', [
                'title' => Request::post('title'),
                'keywords' => Request::post('keywords'),
                'description' => Request::post('description'),
                'admin_title' => Request::post('admin_title'),
            ]);

            Session::set('install_admin', [
                'admin_username' => Request::post('admin_username'),
                'admin_password' => Request::post('admin_password'),
            ]);
            return success(['sql_count' => $sql_count]);
        }
    }


    public function install()
    {
        $database = Session::get('install_databases');

        $sql = Session::get('install_sql');

        $sql_index = Request::post('sql_index') ?: 0;

        $db_config = Config::get('database');

        $db_config['connections']['dynamic'] = $database;

        Config::set($db_config, 'database');

        if ($sql_index >= count($sql)) {
            return  success([
                [
                    'error'   => 0,
                    'message' =>   '数据库安装完成！'
                ]
            ]);
        }
        try {
            $db = Db::connect('dynamic', true);
            $res = sp_execute_sql($db,$sql[$sql_index].';');
        } catch (\PDOException $exception) {
            return error($exception->getMessage(), $exception->getCode(), ['next' => false]);
        }
        return  success($res);
    }

    public function site()
    {
        if(Request::isPost()){
            //设置站点基本信息
            $site = Session::get('install_site');
            foreach (['title','keywords','description','admin_title'] as $value){
                Db::name('config')->where("key",$value)->update([
                    'value' => $site[$value]
                ]);
            }
            return success([
                'error'   => 0,
                'message' =>   '设置站点信息成功！'
            ]);
        }
    }

    public function admin()
    {
        if(Request::isPost()){
            $admin = Session::get("install_admin");
            $date = date('Y-m-d H:i:s');
            $res = Db::name("admin")->insert([
                'rid' => 0,
                'uname' => $admin['admin_username'],
                'pass' => password_hash($admin['admin_password'],1),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'login_time' => $date,
                'create_time' => $date,
            ]);
            if($res){
                return success([
                    'error'   => 0,
                    'message' =>   '增加管理员信息成功！'
                ]);
            }
            return success([
                'error'   => 0,
                'message' =>   '增加管理员信息失败！'
            ]);
        }
    }

    //设置数据库信息
    public function database()
    {
        if(Request::isPost()){
            $database_session = Session::get("install_databases");
            $database_file = file_get_contents(app_path().'data/database.tpl');
            //替换变量
            $database_file = sprintf($database_file,
                $database_session['hostname'],
                $database_session['database'],
                $database_session['username'],
                $database_session['password'],
                $database_session['hostport'],
                $database_session['charset'],
                $database_session['prefix']
            );
            //替换数据库配置文件
            $res = file_put_contents(root_path().'config/database.php',$database_file);
            if($res){
                return success([
                    'error'   => 0,
                    'message' =>   '更新数据库配置文件成功！'
                ]);
            }
            return success([
                'error'   => 0,
                'message' =>   '跟新数据库配置文件失败！'
            ]);
        }
    }

    public function block()
    {
        $path = root_path().'public/install.lock';
        @touch($path);
        return success([
            'error'   => 0,
            'message' =>   '写入锁定文件成功！'
        ]);
    }
}