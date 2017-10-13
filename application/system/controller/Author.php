<?php
/* |------------------------------------------------------
 * | 作者管理
 * |------------------------------------------------------
 * | 列表
 * | 添加
 * | 修改
 * | 删除
 * */
namespace app\system\controller;
use think\Controller;

class Author extends Controller
{
    /* ========== 列表 ========== */
    public function index()
    {
        $author_model = db('author');
        $where = [];
        $author_list = $author_model
            ->where($where)
            ->paginate();
        $this->assign('author_list',$author_list);
        return view();
    }
}