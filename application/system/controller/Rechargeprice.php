<?php
/* |------------------------------------------------------
 * | 书币与会员价格管理
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
use app\system\model\Rechargeprices;
use think\Db;

class Rechargeprice extends Auth
{
    /* ========== 列表 ========== */
    public function index()
    {
        $rechargeprice_model = model('Rechargeprices');
        $where = [];
        /* ++++++++++ 充值类型 ++++++++++ */
        $type=input('type');
        if(is_numeric($type)){
            $where['type'] = "$type";
            $datas['type']=$type;
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
                $rechargeprice_model=$rechargeprice_model->onlyTrashed();
            }
        }else{
            $rechargeprice_model=$rechargeprice_model->withTrashed();
        }
        $rechargeprice_list = $rechargeprice_model
            ->where($where)
            ->order([$ordername=>$orderby])
            ->paginate($display_num);

        $datas['rechargeprice_list']=$rechargeprice_list;
        $this->assign($datas);
        return view();
    }

    /* ========== 添加 ========== */
    public function insert()
    {
        if(request()->isPost()){
            $datas = input();
            $rule = [
                ['price', 'require', '价格必须填写'],
                ['number', 'require', '书币或会员时长必须填写']
            ];
            $result = $this->validate($datas, $rule);
            if (true !== $result) {
                $this->error($result);
            }
            $rechargeprice_model = new Rechargeprices;
            $rs = $rechargeprice_model->add();
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
        $info = model('Rechargeprices')->withTrashed()->where($where)->find();
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
            ['price', 'require', '价格必须填写'],
            ['number', 'require', '书币或会员时长必须填写']
        ];
        $result = $this->validate($datas, $rule);
        if (true !== $result) {
            $this->error($result);
        }
        $rechargeprice_model = new Rechargeprices;
        $rs = $rechargeprice_model->updata();
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
        $rs=Rechargeprices::destroy($ids);
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
        $res=Rechargeprices::onlyTrashed()->whereIn('id',$ids)->delete(true);
        if($res){
            return $this->success('销毁成功','');
        }else{
            return $this->error('销毁失败,请先删除数据！');
        }
    }
}