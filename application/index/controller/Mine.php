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

//        $nickname = $this->reader['nickname'];
        $nickname = '觅树知音';
        $datas['nickname'] = $nickname;

        $datas['book_money'] = $this->reader['book_money'];
//        $datas['headimgurl'] = $this->reader['headimgurl'];
        $this->assign($datas);
        return view();
    }
}