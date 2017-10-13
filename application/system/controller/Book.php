<?php
/* |------------------------------------------------------
 * | 小说管理
 * |------------------------------------------------------
 * | 列表
 * | 添加
 * | 详情
 * | 修改
 * | 删除
 * */
namespace app\system\controller;


class Book extends Auth
{
    /* ========== 列表 ========== */
    public function index()
    {
        $book_model = model('books');
        $where = [];
        /* ----- 查询条件(分类名称) -----*/
        $name = input('name');
        if($name){
            $where['name'] = array('LIKE',"%$name%");
            $this->assign('name',$name);
        }

        $bookcate_list = $bookcate_model
            ->where($where)
            ->paginate();
        $this->assign('bookcate_list',$bookcate_list);
        return view();
    }
}