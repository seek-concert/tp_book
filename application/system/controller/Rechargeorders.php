<?php
/* |------------------------------------------------------
 * | 充值订单管理
 * |------------------------------------------------------
 * | 充值订单列表
 * | 充值订单详情
 * */
namespace app\system\controller;

class Rechargeorders extends Auth
{
    /* ========== 列表 ========== */
    public function index()
    {
        $rechargeorders_model = model('Rechargeorderss');
        $where = [];
        /* ++++++++++ 查询条件(订单号) ++++++++++ */
        $orders_no=input('orders_no');
        if($orders_no){
            $where['orders_no']=$orders_no;
            $datas['orders_no']=$orders_no;
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
        $rechargeorders_list = $rechargeorders_model
            ->field(['b.*','r.openid as openids'])
            ->alias('b')
            ->join('reader r','b.reader_id = r.id','left')
            ->where($where)
            ->order([$ordername=>$orderby])
            ->paginate($display_num);

        $datas['rechargeorders_list']=$rechargeorders_list;
        $this->assign($datas);
        return view();
    }

    /* ========== 基本信息 ========== */
    public function detail(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $where['id'] = $id;
        $info = model('Rechargeorderss')
            ->field(['b.*','r.openid as openids'])
            ->alias('b')
            ->join('reader r','b.reader_id = r.id','left')
            ->where($where)
            ->find();
        $this->assign('info',$info);
        return view('modify');
    }
}