<?php
/* |------------------------------------------------------
 * | 小说管理
 * |------------------------------------------------------
 * | 列表
 * | 添加
 * | 详情
 * | 修改
 * | 删除
 * | 恢复
 * | 销毁
 * | 上下架
 * | 设置热门小说
 * | 设置推荐小说
 * */
namespace app\system\controller;
use app\system\model\Books;
use app\system\model\Bookcontents;
use think\Db;

class Book extends Auth
{
    /* ========== 列表 ========== */
    public function index()
    {
        $book_model = model('Books');
        $where = [];
        /* ----- 查询条件(小说名称) -----*/
        $title = input('title');
        if($title){
            $where['b.title'] = array('LIKE',"%$title%");
            $this->assign('title',$title);
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
                $book_model=$book_model->onlyTrashed();
            }
        }else{
            $book_model=$book_model->withTrashed();
        }
        $book_list = $book_model
            ->field(['b.*','a.name as author_name','c.name as cate_name'])
            ->alias('b')
            ->join('author a','b.author_id = a.id','left')
            ->join('book_cate c','b.cate_id = c.id','left')
            ->where($where)
            ->order([$ordername=>$orderby])
            ->paginate($display_num);

        $datas['book_list']=$book_list;
        $this->assign($datas);
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
        $info = model('Books')->withTrashed()->where($where)->find();
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
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';
        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        if(is_array($ids)){
            $fail_ids=[];
            $del_ids[]='0';
            foreach ($ids as $id){
                $count=Bookcontents::withTrashed()->field('id')->where('book_id',$id)->count();
                if($count){
                    $fail_ids[]=$id;
                }else{
                    $del_ids[]=$id;
                }
            }
            $rs=Books::destroy($del_ids);
            $fail_num=count($fail_ids);
            if($rs){
                if($fail_num){
                    return $this->success('部分小说删除成功！部分小说存在章节，请先删除其全部章节后重试！','');
                }else{
                    return $this->success('删除成功','');
                }
            }else{
                return $this->error('删除失败');
            }
        }else{
            $count=Bookcontents::withTrashed()->field('id')->where('book_id',$ids)->count();
            if($count){
                return $this->error('该小说其下存在章节，请先销毁章节！');
            }
            $rs=Books::destroy($ids);
            if ($rs) {
                return  $this->success('删除成功', '');
            } else {
                return  $this->error('删除失败', '');
            }
        }

    }

    /* ========== 恢复 ========== */
    public function restore(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';

        if(empty($ids)){
            return $this->error('至少选择一项');
        }

        $res=Db::table('book')->whereIn('id',$ids)->update(['deleted_at'=>null,'updated_at'=>time()]);
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
        $res=Books::onlyTrashed()->whereIn('id',$ids)->delete(true);
        if($res){
            return $this->success('销毁成功','');
        }else{
            return $this->error('销毁失败,请先删除数据！');
        }
    }

    /* ========== 上下架 ========== */
    public function modify_online(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';
        $online=$inputs['online'];

        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        if(!in_array($online,[0,1])){
            return $this->error('错误操作');
        }
        $rs=model('Books')->save(['online'=>$online],['id'=>['in',$ids]]);
        if($rs){
            return $this->success('修改成功','');
        }else{
            return $this->error('修改失败');
        }
    }

    /* ========== 设置热门小说 ========== */
    public function modify_hot(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';
        $is_hot=$inputs['is_hot'];

        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        if(!in_array($is_hot,[0,1])){
            return $this->error('错误操作');
        }
        $rs=model('Books')->save(['is_hot'=>$is_hot],['id'=>['in',$ids]]);
        if($rs){
            return $this->success('修改成功','');
        }else{
            return $this->error('修改失败');
        }
    }

    /* ========== 设置推荐小说 ========== */
    public function modify_recommend(){
        $inputs=input();
        $ids=isset($inputs['ids'])?$inputs['ids']:'';
        $is_recommend=$inputs['is_recommend'];

        if(empty($ids)){
            return $this->error('至少选择一项');
        }
        if(!in_array($is_recommend,[0,1])){
            return $this->error('错误操作');
        }
        $rs=model('Books')->save(['is_recommend'=>$is_recommend],['id'=>['in',$ids]]);
        if($rs){
            return $this->success('修改成功','');
        }else{
            return $this->error('修改失败');
        }
    }

    /* ========== 设置小说限时免费 ========== */
    public function modify_freetime(){
        $ids = input('book_ids');
        $where['id'] = array('in',$ids);
        if(!input('free_start')||!input('free_end')){
            return $this->error('请选择限时免费时间','');
        }
       $rs = model('Books')->save(['free_status'=>1,'free_start'=>input('free_start'),'free_end'=>input('free_end')],$where);
        if($rs){
            return $this->success('修改成功','');
        }else{
            return $this->error('修改失败');
        }
    }

}