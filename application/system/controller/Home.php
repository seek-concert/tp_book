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
        return view();
    }

    public function console(){
        return view();
    }
}
