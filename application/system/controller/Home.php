<?php
/* |------------------------------------------------------
 * | 后台导航框架
 * |------------------------------------------------------
 * | 初始化操作
 * | 框架主页
 * | 控制台
 * */
namespace app\system\controller;

class Home extends Auth
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();

    }

    /* ========== 框架主页 ========== */
    public function index()
    {
        $menus=model('Menus')->field(['id','parent_id','name','icon','url','display','status'])->where('display',1)->select();
        $nav_menus='';
        if($menus){
            $nav_menus=get_nav_li_list($menus);
        }

        return view('index',[
            'nav_menus'=>$nav_menus,
        ]);
    }

    /* ========== 控制台 ========== */
    public function console(){
        return view();
    }
}
