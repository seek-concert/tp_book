<?php
/* |------------------------------------------------------
 * | 首页
 * |------------------------------------------------------
 * |
 * */

namespace app\index\controller;

class Index extends Auth
{
    public function index()
    {
        return view();
    }
}
