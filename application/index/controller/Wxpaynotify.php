<?php
/* |------------------------------------------------------
 * | 微信支付回调
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;

use think\Loader;

ini_set('date.timezone','Asia/Shanghai');
Loader::import('WxPay.lib.WxPay#Config');
Loader::import('WxPay.lib.WxPay#Api');
Loader::import('WxPay.lib.WxPay#JsApiPay');
Loader::import('WxPay.lib.WxPay#Notify');

class Wxpaynotify extends \WxPayNotify
{
    public function index()
    {
        $this->Handle(true);
    }

    /**
     *
     * 回调方法入口，子类可重写该方法
     * 注意：
     * 1、微信回调超时时间为2s，建议用户使用异步处理流程，确认成功之后立刻回复微信服务器
     * 2、微信服务器在调用失败或者接到回包为非确认包的时候，会发起重试，需确保你的回调是可以重入
     * @param array $data 回调解释出的参数
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($data, &$msg)
    {
        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        /*//获取服务器返回的数据
           $order_sn = $data['out_trade_no'];  //订单单号
           $order_id = $data['attach'];        //附加参数,选择传递订单ID
           $openid = $data['openid'];          //付款人openID
           $total_fee = $data['total_fee'];    //付款金额*/

        //查询订单
        $order_info=db('recharge_orders')->where('orders_no',$data['out_trade_no'])->find();
        //更新订单
        db('recharge_orders')->where('id',$order_info['id'])->update([
            'trade_no'=>$data["transaction_id"],
            'pay_num'=>$data["total_fee"]/100,
            'result_code'=>$data["result_code"],
            'finished_at'=>time(),
        ]);
        // 更新会员时长
        if($order_info['type']){
            $vip_end=db('reader')->where('id',$order_info['reader_id'])->value('vip_end');
            $add=86400*($order_info['number']+$order_info['gift_num']);
            if($vip_end>time()){
                $end=$vip_end+$add;
            }else{
                $end=time()+$add;
            }
            db('reader')->where('id',$order_info['reader_id'])->update([
                'vip_end'=>$end,
                'updated_at'=>time(),
            ]);
        }

        return true;
    }

    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new \WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = \WxPayApi::orderQuery($input);

        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }
}
