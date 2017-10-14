<?php
/* |------------------------------------------------------
 * | 小说分类管理
 * |------------------------------------------------------
 * | 列表
 * | 添加
 * | 详情
 * | 修改
 * | 删除
 * */

namespace app\system\controller;
use \app\system\Model\Bookcates;

class Bookcate extends Auth
{
    /* ========== 列表 ========== */
    public function index()
    {
        $bookcate_model = model('Bookcates');
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

    /* ========== 添加 ========== */
    public function insert()
    {
        if(request()->isPost()){
            $datas = input();
            $rule = [
                ['name', 'require|unique:book_cate|max:15', '分类名称必须填写|分类已经存在|名称最多不能超过15个字符']
            ];
            $result = $this->validate($datas, $rule);
            if (true !== $result) {
                $this->error($result);
            }
            $bookcate_model = new Bookcates;
            $rs = $bookcate_model->add();
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
        $info = model('Bookcates')->where($where)->find();
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
            ['name', 'require|unique:book_cate,name,'.$id.',id|max:15', '名称必须填写|笔名已经存在|名称最多不能超过15个字符']
        ];
        $result = $this->validate($datas, $rule);
        if (true !== $result) {
            $this->error($result);
        }
        $bookcate_model = new Bookcates;
        $rs = $bookcate_model->updata();
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
        $bookcate_model = new Bookcates;
        $rs = $bookcate_model->dels();
        if ($rs) {
            return  $this->success('删除成功', '');
        } else {
            return  $this->error('删除失败', '');
        }
    }
}