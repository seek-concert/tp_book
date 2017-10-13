<?php
/* |------------------------------------------------------
 * | 作者管理
 * |------------------------------------------------------
 * | 列表
 * | 添加
 * | 详情
 * | 修改
 * | 删除
 * */
namespace app\system\controller;
use \app\system\Model\Authors;

class Author extends Auth
{
    /* ========== 列表 ========== */
    public function index()
    {
        $author_model = model('Authors');
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
    public function insert()
    {
        if(request()->isPost()){
            $datas = input();
            $rule = [
                ['name', 'require|unique:author|max:15', '名称必须填写|笔名已经存在|名称最多不能超过15个字符'],
                ['realname', 'require|max:15', '真实姓名必须填写|真实姓名最多不能超过15个字符'],
                ['phone', 'require|number','电话必须填写|电话必须是数字']
            ];
            $result = $this->validate($datas, $rule);
            if (true !== $result) {
                $this->error($result);
            }
            $author_model = new Authors;
            $rs = $author_model->add();
            if ($rs) {
                return  $this->success('添加成功', '');
            } else {
                return  $this->error('添加失败', '');
            }
        }else{
            return view('modify');
        }
    }

    /* ========== 详情 ========== */
    public function detail(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $where['id'] = $id;
        $info = model('Authors')->where($where)->find();
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
            ['name', 'require|unique:author,name,'.$id.',id|max:15', '名称必须填写|笔名已经存在|名称最多不能超过15个字符'],
            ['realname', 'require|max:15', '真实姓名必须填写|真实姓名最多不能超过15个字符'],
            ['phone', 'require|number','电话必须填写|电话必须是数字']
        ];
        $result = $this->validate($datas, $rule);
        if (true !== $result) {
            $this->error($result);
        }
        $author_model = new Authors;
        $rs = $author_model->updata();
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
        $author_model = new Authors;
        $rs = $author_model->dels();
        if ($rs) {
            return  $this->success('删除成功', '');
        } else {
            return  $this->error('删除失败', '');
        }
    }


}