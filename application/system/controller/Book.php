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
use app\system\model\Books;

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

        $book_list = $book_model
            ->where($where)
            ->paginate();
        $this->assign('book_list',$book_list);
        return view();
    }

    /* ========== 添加 ========== */
    public function insert()
    {
        if(request()->isPost()){
            $datas = input();
            $rule = [
                ['title', 'require|max:15', '小说名称必须填写|名称最多不能超过15个字符'],
                ['cate_id', 'require', '请选择小说分类'],
                ['author_id', 'require', '请选择作者'],
                ['summary', 'require', '请填写小说摘要']
            ];
            $result = $this->validate($datas, $rule);
            if (true !== $result) {
                $this->error($result);
            }
            $book_model = new Books;
            $rs = $book_model->add();
            if ($rs) {
                return  $this->success('添加成功', '');
            } else {
                return  $this->error('添加失败', '');
            }
        }else{
            $bookcate_name = model('Bookcates')->field('id,name')->select();
            $this->assign('bookcate_name',$bookcate_name);
            $author_name = model('Authors')->field('id,name')->select();
            $this->assign('author_name',$author_name);
            return view('modify');
        }
    }

    /* ========== 详情 ========== */
    public function detail(){
        $bookcate_name = model('Bookcates')->field('id,name')->select();
        $this->assign('bookcate_name',$bookcate_name);
        $author_name = model('Authors')->field('id,name')->select();
        $this->assign('author_name',$author_name);
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $where['id'] = $id;
        $info = model('Books')->where($where)->find();
        $this->assign('info',$info);
        return view('modify');
    }

    /* ========== 修改 ========== */
    public function modify(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $datas = input();
        $rule = [
            ['title', 'require|max:15', '小说名称必须填写|名称最多不能超过15个字符'],
            ['cate_id', 'require', '请选择小说分类'],
            ['author_id', 'require', '请选择作者'],
            ['summary', 'require', '请填写小说摘要']
        ];
        $result = $this->validate($datas, $rule);
        if (true !== $result) {
            $this->error($result);
        }
        $book_model = new Books;
        $rs = $book_model->updata();
        if ($rs) {
            return  $this->success('更新成功', '');
        } else {
            return  $this->error('更新失败', '');
        }
    }

    /* ========== 删除 ========== */
    public function del(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $book_model = new Books;
        $rs = $book_model->dels();
        if ($rs) {
            return  $this->success('删除成功', '');
        } else {
            return  $this->error('删除失败', '');
        }
    }

}