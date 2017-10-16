<?php
/* |------------------------------------------------------
 * | 小说章节内容管理
 * |------------------------------------------------------
 * | 列表
 * | 添加
 * | 详情
 * | 修改
 * | 删除
 * */
namespace app\system\controller;
use app\system\model\Bookcontents;

class Bookcontent extends Auth
{
    /* ========== 列表 ========== */
    public function index()
    {
        $bookcontent_model = model('Bookcontents');
        $where = [];
        /* ----- 查询条件(小说名称) -----*/
        $title = input('title');
        if($title){
            $where['title'] = array('LIKE',"%$title%");
            $this->assign('title',$title);
        }
        $where['book_id'] = input('book_id');
        $bookcontent_list = $bookcontent_model
            ->where($where)
            ->paginate();
        $this->assign('bookcontent_list',$bookcontent_list);
        return view();
    }
}