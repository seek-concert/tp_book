<?php
/* |------------------------------------------------------
 * | 免费专区
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;


class Free extends Auth
{
    public $data_setting;
    public function _initialize()
    {
        parent::_initialize();
        $this->data_setting=db('data_setting')->field(['ranking_top','ranking_total','pagenum'])->find();
    }
    /* ============ 免费列表 ============== */
    public function index(){
        $book_model = model('Books');
        /*+++++ 限时免费 +++++*/
        $freestart = strtotime(date('Y-m-d')." 00:00:00");
        $freeend = strtotime(date('Y-m-d')." 23:59:59");
        $timelimit = $book_model
            ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
            ->alias('b')
            ->join('author a','b.author_id = a.id','left')
            ->where('free_start','<=',$freestart)
            ->where('free_end','>=',$freeend)
            ->where('online',1)
            ->order('sort desc')
            ->select();
        $timelimit_count = count($timelimit);
        $datas['timelimit_count'] = $timelimit_count;
        $datas['timelimit'] = $timelimit;
        $datas['pagenum'] = $this->data_setting['pagenum'];
        $this->assign($datas);

        return view();
    }

    /* ============ 免费下一页 ============== */
    public function getnext(){
        $book_model = model('Books');

        $fenye_conut = input('fenye_conut');
        if($fenye_conut){
            $pagenum = $this->data_setting['pagenum']*$fenye_conut;
        }else{
            $pagenum = $this->data_setting['pagenum'];
        }
        /*+++++ 完全免费 +++++*/
        $free_book = $book_model
            ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
            ->alias('b')
            ->join('author a','b.author_id = a.id','left')
            ->where('free_status',2)
            ->where('online',1)
            ->paginate($pagenum);
        if($free_book){
            return $this->success('获取成功','',$free_book);
        }else{
            return $this->error('没有了！');
        }
    }
}