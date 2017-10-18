<?php
/* |------------------------------------------------------
 * | 充值
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;

class Recharge extends Auth
{
    /* ============ 充值列表 ============== */
    public function index()
    {
        return view();
    }

    /* ============ 充值下单 ============== */
    public function orders(){
        ini_set('date.timezone','Asia/Shanghai');
        Vendor('WxPay.lib.WxPay#Config');
        Vendor('WxPay.lib.WxPay#Api');
        Vendor('WxPay.lib.WxPay#JsApiPay');

        $id=input('id');
        if(!$id){
            return $this->error('参数错误！');
        }
        /* ++++++++++充值基本信息++++++++++ */


        /* ++++++++++发起支付++++++++++ */

        /* ①、获取用户openid */
        $tools = new \JsApiPay();
        $openId = $this->reader['openid']?$this->reader['openid']:$tools->GetOpenid();

        /* ②、统一下单 */
        $orderno=\WxPayConfig::MCHID.date("YmdHis");
        $notify_url='';

        $input = new \WxPayUnifiedOrder();
        $input->SetBody("充值".$id.'分'); /* 商品名称 */
        $input->SetAttach("");  /* 附加参数,可填可不填,填写的话,里边字符串不能出现空格 */
        $input->SetOut_trade_no($orderno); /* 订单号 */
        $input->SetTotal_fee($id); /* 支付金额,单位:分 */
        $input->SetTime_start(date("YmdHis")); /* 支付发起时间 */
        $input->SetTime_expire(date("YmdHis", time() + 600));  /* 支付超时 */
        $input->SetGoods_tag(""); /* 订单优惠标记 */
        $input->SetNotify_url($notify_url); /* 支付回调验证地址 */
        $input->SetTrade_type("JSAPI"); /* 支付类型 */
        $input->SetOpenid($openId); /* 用户openID */
        $order = \WxPayApi::unifiedOrder($input); /* 统一下单 */

        $jsApiParameters = $tools->GetJsApiParameters($order);

        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();

        /* ++++++++++充值订单++++++++++ */


        $data['code']='0';
        $data['info']='提交成功';
        $data['data']['jsApiParameters']=json_decode($jsApiParameters,true);
        $data['data']['editAddress']=json_decode($editAddress,true);

        $this->assign([
            'jsApiParameters'=>$jsApiParameters,
            'editAddress'=>$editAddress,
        ]);

        return view();
    }
}
