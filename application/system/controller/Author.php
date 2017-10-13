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
        /* ----- 查询条件(作者笔名) -----*/
        $name = input('name');
        if($name){
            $where['name'] = array('LIKE',"%$name%");
            $this->assign('name',$name);
        }
        /* ----- 查询条件(作者真实姓名) -----*/
        $realname = input('realname');
        if($realname){
            $where['realname'] = array('LIKE',"%$realname%");
            $this->assign('realname',$realname);
        }

        $author_list = $author_model
            ->where($where)
            ->paginate();
        $this->assign('author_list',$author_list);
        return view();
    }
    /* ========== 添加 ========== */
    public function insert(){
        if(request()->isPost()){
            $author_model = model('authors');
            $rs =$author_model->add();
              if($rs){
                  return $this->success('添加成功','',1);
              }else{
                  return $this->error('添加失败','',1);
              }
        }else{
            return view('modify');
        }
    }
}