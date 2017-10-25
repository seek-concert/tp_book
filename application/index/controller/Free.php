<?php
/* |------------------------------------------------------
 * | 免费专区
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;


class Free extends Auth
{
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
        /*+++++ 完全免费 +++++*/
        $free_book = $book_model
                ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
                ->alias('b')
                ->join('author a','b.author_id = a.id','left')
                ->where('free_status',2)
                ->where('online',1)
                ->select();
        $datas['free_book'] = $free_book;
        $this->assign($datas);

        return view();
    }
}