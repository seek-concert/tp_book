<?php
/* |------------------------------------------------------
 * | 小说书架
 * |------------------------------------------------------
 * |
 * | 我的书架
 * | 最近阅读
 * | 我的书签
 * */

namespace app\index\controller;


class Bookshelf extends Auth
{
    /* ============ 我的书架 ============== */
    public function index(){
        return view();
    }
}