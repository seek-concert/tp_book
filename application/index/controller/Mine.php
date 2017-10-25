<?php
/* |------------------------------------------------------
 * | 个人中心
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;


class Mine extends Auth
{
    /* ============ 个人中心 ============== */
    public function index(){
//        $reader_id = $this->reader['id'];
        $reader_id = 10001;
        $datas['reader_id'] = $reader_id;
        $reader_name = '觅树知音';
        $datas['reader_name'] = $reader_name;
//        $book_money = db('reader')
//            ->field('book_money')
//            ->where('id',$reader_id)
//            ->find();
        $book_money = 20;
        $datas['book_money'] = $book_money;
        $this->assign($datas);
        dump($this->reader);
        return view();
    }
}