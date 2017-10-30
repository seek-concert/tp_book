<?php
/* |------------------------------------------------------
 * | 个人中心
 * |------------------------------------------------------
 * |
 * |个人中心
 * |联系客服
 * */
namespace app\index\controller;

class Mine extends Auth
{
    /* ============ 个人中心 ============== */
    public function index(){
        $datas=$this->reader;

        $vip_id = db('recharge_price')->field('id')->where('price','365')->find();
        $datas['vip_id']=$vip_id['id'];
        /*+++++ banner图 +++++*/
        $banner_img = db('banner')->field(['picture'])->where('type',2)->where('deleted_at is null')->find();
        $datas['banner_img'] = $banner_img;
        $this->assign($datas);
        return view();
    }

    /* ============ 联系客服 ============== */
    public function kefu(){
        $qr_code = db('setting')->field('qr_code')->find();
        $datas['qr_code'] = $qr_code;
        $this->assign($datas);
        return view();
    }
}