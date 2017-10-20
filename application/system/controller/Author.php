<?php
/* |------------------------------------------------------
 * | 作者管理
 * |------------------------------------------------------
 * | 列表
 * | 添加
 * | 详情
 * | 修改
 * | 删除
 * | 恢复
 * | 销毁
 * */
namespace app\system\controller;
use \app\system\model\Authors;
use think\Db;

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
            $datas['name']=$name;
        }
        /* ----- 查询条件(作者真实姓名) -----*/
        $realname = input('realname');
        if($realname){
            $where['realname'] = array('LIKE',"%$realname%");
            $datas['realname']=$realname;
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
                $author_model=$author_model->onlyTrashed();
            }
        }else{
            $author_model=$author_model->withTrashed();
        }
        $author_list = $author_model
            ->where($where)
            ->order([$ordername=>$orderby])
            ->paginate($display_num);
        $datas['author_list']=$author_list;
        $this->assign($datas);
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
        $info = model('Authors')->withTrashed()->where($where)->find();
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
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';
        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        $rs=Authors::destroy($ids);
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

        $res=Db::table('author')->whereIn('id',$ids)->update(['deleted_at'=>null,'updated_at'=>time()]);
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
        $res=Authors::onlyTrashed()->whereIn('id',$ids)->delete(true);
        if($res){
            return $this->success('销毁成功','');
        }else{
            return $this->error('销毁失败,请先删除数据！');
        }
    }
}