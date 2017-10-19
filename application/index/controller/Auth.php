<?php
/* |------------------------------------------------------
 * | 初始化
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;

use think\Controller;
use app\index\model\Readers;

class Auth extends Controller
{
    public $reader;

    public function _initialize()
    {
        if(session('remember_url')){
            $url=session('remember_url');
            session('remember_url',null);
            $this->redirect($url);
        }
        /* +++++++++获取读者信息+++++++++ */
        $openid=cookie('openid');
        if($openid){
            cookie('openid',$openid,3*24*60*60);
        }else{
            session('remember_url',request()->url());
            $this->redirect('Base/index');
        }

        $model=new Readers();
        $reader=$model->where('openid',$openid)->find();
        if(!$reader){
            $data['openid']=$openid;
            $data['book_money']=0;
            $data['vip_end']=0;
            $reader=$model->save($data);
        }
        if(!session('openid')){
            $reader->save($model->login_data());
            session('openid',$openid);
        }
        $wx_info=$this->getuserinfo();
        $reader_info=$reader->toArray();
        $this->reader=array_merge($reader_info,$wx_info);
    }

    /* ============获取微信用户信息============== */
    public function getuserinfo(){
        $check_valid_url=' https://api.weixin.qq.com/sns/auth?access_token='.cookie('access_token').'&openid='.cookie('openid');
        $result=https_request($check_valid_url);
        $result=json_decode($result,true);
        if($result['errcode']){
            $base=new Base();
            $base->refreshtoken();
        }

        $userinfo_url='https://api.weixin.qq.com/sns/userinfo?access_token='.cookie('access_token').'&openid='.cookie('openid').'&lang=zh_CN';
        $result=https_request($userinfo_url);

        $result=json_decode($result,true);
        return $result;
    }
}