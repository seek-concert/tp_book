<?php
/* |------------------------------------------------------
 * | 排行榜
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;

use app\index\model\Books;

class Ranking extends Auth
{
    public $data_setting;
    public function _initialize()
    {
        parent::_initialize();
        $this->data_setting=db('data_setting')->field(['ranking_top','ranking_total','pagenum'])->find();
    }

    /* ============ 排行首页 置顶排行 ============== */
    public function index(){
        $book_model=new Books();
        /* +++++++++ 点击榜 +++++++++ */
        $click_list=$book_model->field(['b.id','b.title','b.author_id','b.online','b.click_num','a.name as author_name'])
            ->alias('b')
            ->join('author a','a.id=b.author_id','left')
            ->where('b.online',1)
            ->order('b.click_num desc')
            ->limit($this->data_setting['ranking_top'])
            ->select();

        /* +++++++++ 留存榜 +++++++++ */
        $submit_list=$book_model->field(['b.id','b.title','b.author_id','b.online','b.submit_num','a.name as author_name'])
            ->alias('b')
            ->join('author a','a.id=b.author_id','left')
            ->where('b.online',1)
            ->order('b.submit_num desc')
            ->limit($this->data_setting['ranking_top'])
            ->select();

        /* +++++++++ 购买榜 +++++++++ */
        $buy_list=$book_model->field(['b.id','b.title','b.author_id','b.online','b.buy_num','a.name as author_name'])
            ->alias('b')
            ->join('author a','a.id=b.author_id','left')
            ->where('b.online',1)
            ->order('b.buy_num desc')
            ->limit($this->data_setting['ranking_top'])
            ->select();

        $this->assign([
            'data_setting'=>$this->data_setting,
            'click_list'=>$click_list,
            'submit_list'=>$submit_list,
            'buy_list'=>$buy_list,
        ]);

        return view();
    }

    /* ============ 排行详情 ============== */
    public function detail(){
        $type=input('type');
        if(!in_array($type,['click','submit','buy'])){
            return $this->error('操作错误！');
        }
        return view();
    }

    /* ============ 排行下一页 ============== */
    public function getnext(){
        $type=input('type');
        if(!in_array($type,['click','submit','buy'])){
            return $this->error('操作错误！');
        }
        $book_model=new Books();
        $ranking_list=$book_model->field(['b.id','b.title','b.picture','b.summary','b.author_id','b.online','b.'.$type.'_num','a.name as author_name'])
            ->alias('b')
            ->join('author a','a.id=b.author_id','left')
            ->where('b.online',1)
            ->order('b.'.$type.'_num desc')
            ->select();
        $datas['ranking_list'] = $ranking_list;
        $datas['pagenum'] = $this->data_setting['pagenum'];
        if($ranking_list){
            return $this->success('获取成功','',$datas);
        }else{
            return $this->error('没有了！');
        }
    }
}