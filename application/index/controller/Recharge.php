<?php
/* |------------------------------------------------------
 * | 充值与会员
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;
use app\index\model\Rechargeprices;
use think\Loader;

class Recharge extends Auth
{
    /* ============ 充值列表 ============== */
    public function index()
    {
        $recharge_list=Rechargeprices::field(['id','type','price','number','gift_num'])->order('type asc,price asc')->select();
        $this->assign(['recharge_list'=>$recharge_list]);
        return view();
    }

    /* ============ 微信 统一下单 ============== */
    public function orders(){
        ini_set('date.timezone','Asia/Shanghai');
        Loader::import('WxPay.lib.WxPay#Config');
        Loader::import('WxPay.lib.WxPay#Api');
//        Loader::import('WxPay.lib.WxPay#Data');
        Loader::import('WxPay.lib.WxPay#JsApiPay');

        $id=input('id');
        if(!$id){
            return $this->error('参数错误！');
        }
        /* ++++++++++充值基本信息++++++++++ */
        $recharge=db('recharge_price')->where('id',$id)->where('deleted_at IS NULL')->find();
          if($recharge['type']==0){
              $shoping_name = "充值".$recharge['number']."书币";
          }
          if($recharge['type']==1){
              $shoping_name = "会员充值".$recharge['number']."天";
          }
        /* ++++++++++发起支付下单++++++++++ */
        /* ①、获取用户openid */
        $tools = new \JsApiPay();
        $openId = $this->reader['openid'];

        /* ②、统一下单 */
        $orderno=\WxPayConfig::MCHID.date("YmdHis");
        $domain=request()->domain();
        $notify_url=$domain.'/index/Wxpaynotify/index';

        $input = new \WxPayUnifiedOrder();
        $input->SetBody($shoping_name); /* 商品名称 */
        $input->SetAttach("");  /* 附加参数,可填可不填,填写的话,里边字符串不能出现空格 */
        $input->SetOut_trade_no($orderno); /* 订单号 */
//        $input->SetTotal_fee($recharge['price']*100); /* 支付金额,单位:分 */
        $input->SetTotal_fee(1); /* 支付金额,单位:分 */
        $input->SetTime_start(date("YmdHis")); /* 支付发起时间 */
        $input->SetTime_expire(date("YmdHis", strtotime('+10 min')));  /* 支付超时 */
        $input->SetGoods_tag(""); /* 订单优惠标记 */
        $input->SetNotify_url($notify_url); /* 支付回调验证地址 */
        $input->SetTrade_type("JSAPI"); /* 支付类型 */
        $input->SetOpenid($openId); /* 用户openID */
        $order = \WxPayApi::unifiedOrder($input); /* 统一下单 */

        $jsApiParameters = $tools->GetJsApiParameters($order);

        //获取共享收货地址js函数参数
//        $editAddress = $tools->GetEditAddressParameters();

        /* ++++++++++充值订单++++++++++ */
        $orders_datas['type']=$recharge['type'];
        $orders_datas['orders_no']=$orderno;
        $orders_datas['reader_id']=$this->reader['id'];
        $orders_datas['price']=$recharge['price'];
        $orders_datas['number']=$recharge['number'];
        $orders_datas['gift_num']=$recharge['gift_num'];
        $orders_datas['created_at']=time();

        $res=db('recharge_orders')->insert($orders_datas);

        $this->assign([
            'jsApiParameters'=>$jsApiParameters,
            'orderno'=>$orderno,
            'buy_time'=>date("Y-m-d H:i:s"),
            'price'=>$recharge['price'],
            'shoping_name'=>$shoping_name
        ]);

        return view();
    }
}
