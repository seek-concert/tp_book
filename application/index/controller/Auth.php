<?php

namespace app\index\controller;

use think\Controller;

class Auth extends Controller
{
    public $AppId;
    public $AppSecret;
    public $reader;
    public function _initialize()
    {
        /* +++++++++授权登录+++++++++ */
        $openid=cookie('openid');
        if($openid){
            cookie('openid',$openid,3*24*60*60);
            if(!cookie('refresh_token')){
                $this->authorize();exit;
            }
        }else{
            if(cookie('refresh_token')){
                $this->refreshtoken();
                $openid=cookie('openid');
            }else{
                $this->authorize();exit;
            }
        }
        /* +++++++++获取微信用户信息+++++++++ */
        if(!$this->reader){
            $userinfo=$this->getuserinfo();
            if($userinfo['errcode']){
                $this->authorize();
            }
            $this->reader=$userinfo;
        }

    }

    /* ============授权获取微信CODE============== */
    public function authorize(){
        $return_url='http://'.$_SERVER['HTTP_HOST'].'/index.php/Index/getaccesstoken';
        $code_url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->AppId.'&redirect_uri='.urlencode($return_url).'&response_type=code&scope=snsapi_userinfo&state=code#wechat_redirect';
        header('location:'.$code_url);
        exit;
    }

    /* ===========用CODE换取微信用户OPENID============== */
    public function getaccesstoken(){
        $get=$_GET;
        $access_token_url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->AppId.'&secret='.$this->AppSecret.'&code='.$get['code'].'&grant_type=authorization_code';
        $result=https_request($access_token_url);
        $result=json_decode($result,true);
        cookie('access_token',$result['access_token'],6000);
        cookie('refresh_token',$result['refresh_token'],29*24*60*60);
        cookie('openid',$result['openid'],3*24*60*60);
        $this->redirect('index');
    }

    /* ============刷新ACCESS_TOKEN============== */
    public function refreshtoken(){
        $refresh_token_url='https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->AppId.'&grant_type=refresh_token&refresh_token='.cookie('refresh_token');
        $result=https_request($refresh_token_url);
        $result=json_decode($result,true);
        cookie('access_token',$result['access_token'],6000);
        cookie('refresh_token',$result['refresh_token'],29*24*60*60);
        cookie('openid',$result['openid'],3*24*60*60);
        return $result;
    }

    /* ============获取微信用户信息============== */
    public function getuserinfo(){
        $check_valid_url=' https://api.weixin.qq.com/sns/auth?access_token='.cookie('access_token').'&openid='.cookie('openid');
        $result=https_request($check_valid_url);
        $result=json_decode($result,true);
        if($result['errcode']){
            $this->refreshtoken();
        }

        $userinfo_url='https://api.weixin.qq.com/sns/userinfo?access_token='.cookie('access_token').'&openid='.cookie('openid').'&lang=zh_CN';
        $result=https_request($userinfo_url);

        $result=json_decode($result,true);
        return $result;
    }
}