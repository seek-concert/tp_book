<?php
/* |------------------------------------------------------
 * | 后台控制初始化
 * |------------------------------------------------------
 * |
 * */
namespace app\system\controller;

use think\Controller;
use think\Session;

class Auth extends Controller
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        /* ++++++++++ 未登录或操作超时 ++++++++++ */
        $userinfo=Session::get('userinfo');
        if(!$userinfo || time()-$userinfo['time']>5){
            $this->redirect('system/Index/index');
        }else{
            Session::set('userinfo.time',time());
        }
    }
}
