<?php
/* |------------------------------------------------------
 * | 读者基本信息
 * |------------------------------------------------------
 * | 读者列表
 * | 读者基本信息
 * | 读者书签
 * | 读者书架
 * | 最近阅读
 * */
namespace app\system\controller;

class Reader extends Auth
{
    /* ========== 列表 ========== */
    public function index()
    {
        $reader_model = model('readers');
        $where = [];
        /* ++++++++++ 微信openid ++++++++++ */
        $openid=input('openid');
        $openid=$openid?$openid:'';
        $datas['openid']=$openid;
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
        $reader_list = $reader_model
            ->where($where)
            ->order([$ordername=>$orderby])
            ->paginate($display_num);

        $datas['reader_list']=$reader_list;
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
        $info = model('Readers')->where($where)->find();
        $this->assign('info',$info);
        return view('modify');
    }

    /* ========== 读者书签 ========== */
    public function bookmark(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $readerbookmark_model = model('Readerbookmark');
        $where = [];
        $where['reader_id'] = $id;
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
        $readerbookmark_list = $readerbookmark_model
            ->order([$ordername=>$orderby])
            ->paginate($display_num);

        $datas['readerbookmark_list']=$readerbookmark_list;
        $this->assign($datas);
        return view();
    }

    /* ========== 读者书架 ========== */
    public function bookshelf(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $readbookshelf_model = model('Readbookshelf');
        $where = [];
        $where['reader_id'] = $id;
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
        $readbookshelf_list = $readbookshelf_model
            ->order([$ordername=>$orderby])
            ->paginate($display_num);

        $datas['readbookshelf_list']=$readbookshelf_list;
        $this->assign($datas);
        return view();
    }

    /* ========== 最近阅读 ========== */
    public function readlast(){
        $id = input('id');
        if(empty($id)){
            return $this->error('非法操作','');
        }
        $readerreadlast_model = model('Readerreadlast');
        $where = [];
        $where['reader_id'] = $id;
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
        $readerreadlast_list = $readerreadlast_model
            ->order([$ordername=>$orderby])
            ->paginate($display_num);

        $datas['readerreadlast_list']=$readerreadlast_list;
        $this->assign($datas);
        return view();
    }
}