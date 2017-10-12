<?php
/* |------------------------------------------------------
 * | 功能与菜单
 * |------------------------------------------------------
 * | 初始化操作
 * | 列表
 * | 添加
 * */
namespace app\system\controller;

class Menu extends Auth
{
    /* ========== 初始化 ========== */
    public function _initialize()
    {
        parent::_initialize();

    }

    /* ========== 列表 ========== */
    public function index()
    {
        return view();
    }

    /* ========== 添加 ========== */
    public function add(){
        return view();
    }
}
