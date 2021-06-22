<?php
namespace backend\middleware;
use app\admin\model\auth\Menu;
use element\facade\Rending;
use think\facade\Db;
use think\facade\Session;
use think\facade\View;

/**
 * Class Auth
 * @package backend\middleware
 * 中间件
 */
class  Auth
{
    /**
     * @var array
     */
    public $white_controller  = ['index'];

    /**
     * @var int[]
     */
    public $white_admin_ids = [1];


    public function handle($request,\Closure $next)
    {
        $request->login_info = $this->checkLogin();
        $this->checkAuth($request);
        return $next($request);
    }


    public function checkLogin()
    {
        if(!Session::has('login_info'))  {
            redirect(url("Login/index"))->send();
            die();
        }
        $login_info = Session::get('login_info');
        View::assign('login_info',$login_info);
        return $login_info;
    }


    public function checkAuth($request)
    {
        $controller =  strtolower($request->controller());
        $action = strtolower($request->action());
        $modelMenu = new Menu();
        $current_menu = '/admin/' . strtolower($controller) . '/' . strtolower($action);
        $current_menu = $modelMenu->getRowByRoute($current_menu);
        $login_id = $request->login_info['aid'];
        Rending::breadcrumb($current_menu,$modelMenu);
        $roles  = Db::name('admin')
            ->alias('a')
            ->where("a.id",$login_id)
            ->leftJoin('auth_role r','a.rid = r.id')
            ->find();
        Rending::aside($roles['routes']);

        View::assign([
            'controller' => $controller,
            'current_route_id' => $current_menu['id']??1,
        ]);
        if(in_array($controller,$this->white_controller)){
            return  true;
        }
        if(in_array($login_id,$this->white_admin_ids)){
           return  true;
        }
        if(!in_array($current_menu['id'],explode(',',$roles['routes']))){
            if($request->isPost()){
                header("content-Type: application/json; charset=utf-8");
               die(json_encode([
                   'code' => 0,
                   'msg' => '你没有权限操作此项，请联系管理员',
                   'data' => []
               ]));
            }
            redirect(url("index/jurisdiction"))->send();
            die();
        }
    }
}
