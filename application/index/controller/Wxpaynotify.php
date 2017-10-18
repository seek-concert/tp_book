<?php
/* |------------------------------------------------------
 * | 支付回调
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;

use think\Loader;

ini_set('date.timezone','Asia/Shanghai');
Loader::import('WxPay.lib.WxPay#Config');
Loader::import('WxPay.lib.WxPay#Api');
Loader::import('WxPay.lib.WxPay#JsApiPay');

class Wxpaynotify extends \WxPayNotify
{
    public function index()
    {
        $this->Handle(false);
    }
}
