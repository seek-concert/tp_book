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
use think\Db;

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
            $datas['name']=$name;
        }
        /* ++++++++++ 排序 ++++++++++ */
        $ordername=input('ordername');
        $ordername=$ordername?$ordername:'id';
        $datas['ordername']=$ordername;

        $orderby=input('orderby');
        $orderby=$orderby?$orderby:'asc';
        $datas['orderby']=$orderby;
        /* ++++++++++ 每页条数 ++++++++++ */
        $nums=[config('paginate.list_rows'),30,50,100,200,500];
        sort($nums);
        $datas['nums']=$nums;
        $display_num=input('display_num');
        $display_num=$display_num?$display_num:config('paginate.list_rows');
        $datas['display_num']=$display_num;
        /* ++++++++++ 是否删除 ++++++++++ */
        $deleted=input('deleted');
        if(is_numeric($deleted) && in_array($deleted,[0,1])){
            $datas['deleted']=$deleted;
            if($deleted==1){
                $bookcate_model=$bookcate_model->onlyTrashed();
            }
        }else{
            $bookcate_model=$bookcate_model->withTrashed();
        }
        $bookcate_list = $bookcate_model
            ->where($where)
            ->order([$ordername=>$orderby])
            ->paginate($display_num);

        $datas['bookcate_list']=$bookcate_list;
        $this->assign($datas);
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
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';
        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        $rs=Bookcates::destroy($ids);
        if ($rs) {
            return  $this->success('删除成功', '');
        } else {
            return  $this->error('删除失败', '');
        }
    }

    /* ========== 恢复 ========== */
    public function restore(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';

        if(empty($ids)){
            return $this->error('至少选择一项');
        }

        $res=Db::table('book_cate')->whereIn('id',$ids)->update(['deleted_at'=>null,'updated_at'=>time()]);
        if($res){
            return $this->success('恢复成功','');
        }else{
            return $this->error('恢复失败');
        }
    }

    /* ========== 销毁 ========== */
    public function destroy(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';

        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        $res=Bookcates::onlyTrashed()->whereIn('id',$ids)->delete(true);
        if($res){
            return $this->success('销毁成功','');
        }else{
            return $this->error('销毁失败');
        }
    }
}