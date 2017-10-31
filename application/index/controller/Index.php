<?php
/* |------------------------------------------------------
 * | 小说首页
 * |------------------------------------------------------
 * |
 * | 首页
 * | 首页（换一批）
 * | 小说详情
 * | 小说详情（换一批）
 * | 小说目录
 * | 搜索列表页
 * | 小说收费
 * | 小说内容
 * | 添加书签
 * */

namespace app\index\controller;

use app\index\model\Bookcontents;
use app\index\model\Books;
use think\Db;

class Index extends Auth
{
    /* ============ 首页 ============== */
    public function index()
    {
        $book_model = model('Books');
        $data_setting = db('data_setting')->find();
        $datas['data_setting'] = $data_setting;
        /*+++++ banner图 +++++*/
        $banner_img = db('banner')->field(['jump_url','picture'])->where('type',1)->where('deleted_at is null')->select();
        $banner_count = count($banner_img);
        $datas['banner_img'] = $banner_img;
        $datas['banner_count'] = $banner_count;
        /*+++++ 主编推荐 +++++*/
        $is_recommend = $book_model
            ->field(['id','picture','title'])
            ->where('is_recommend',1)
            ->where('online',1)
            ->order('sort desc')
            ->limit($data_setting['recommend'])
            ->select();
        $datas['is_recommend'] = $is_recommend;
        /*+++++ 热门小说 +++++*/
        $is_hot = $book_model
            ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
            ->alias('b')
            ->join('author a','b.author_id = a.id','left')
            ->where('is_hot',1)
            ->where('online',1)
            ->order('sort desc')
            ->limit($data_setting['hot'])
            ->select();
        $datas['is_hot'] = $is_hot;
        /*+++++ 新书推荐 +++++*/
        $created_at = $book_model
            ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
            ->alias('b')
            ->join('author a','b.author_id = a.id','left')
            ->where('online',1)
            ->order('b.created_at desc,b.sort asc')
            ->limit($data_setting['new_recommend'])
            ->select();
        $datas['created_at'] = $created_at;
        /*+++++ 限时免费 +++++*/
        $freestart = strtotime(date('Y-m-d')." 00:00:00");
        $freeend = strtotime(date('Y-m-d')." 23:59:59");
        $timelimit = $book_model
            ->field(['id','picture','title'])
            ->where('free_start','<=',$freestart)
            ->where('free_end','>=',$freeend)
            ->where('online',1)
            ->order('sort desc')
            ->limit($data_setting['free'])
            ->select();
        $datas['timelimit'] = $timelimit;
        /*+++++ 猜你喜欢 +++++*/
        $reader_id = $this->reader['id'];
        $book_ids = db('reader_bookshelf')
            ->where('reader_id',$reader_id)
            ->column('book_id');
        $cate_ids = db('book')
            ->where('id','in',$book_ids)
            ->column('cate_id');
        if($cate_ids){
            $cate_count = array_count_values($cate_ids);
            $cate_id = array_search(max($cate_count),$cate_count);
            $like_book = model('books')
                ->field(['id','title','picture'])
                ->where('cate_id',$cate_id)
                ->where('online',1)
                ->limit($data_setting['guess'])
                ->select();
        }else{
            $like_book = [];
        }
        $datas['like_book'] = $like_book;
        /*+++++ 畅销书单 +++++*/
        $buy_num_book = model('books')
            ->field(['b.id','b.title','a.name as author_name'])
            ->alias('b')
            ->join('author a','b.author_id = a.id','left')
            ->where('online',1)
            ->order('buy_num desc')
            ->limit($data_setting['buy'])
            ->select();
        $datas['buy_num_book'] = $buy_num_book;
        /*+++++ 二维码 +++++*/
        $qr_code = db('setting')->field('qr_code')->find();
        $datas['qr_code'] = $qr_code;
        $this->assign($datas);
        return view();
    }

    /* ============ 首页换一批 ============== */
    public function replace_book(){
        $data_setting = db('data_setting')->find();
        $datas['data_setting'] = $data_setting;
        /*+++++ 猜你喜欢(换一批) +++++*/
        $reader_id = $this->reader['id'];

        $book_ids = db('reader_bookshelf')
            ->where('reader_id',$reader_id)
            ->column('book_id');
        $cate_ids = db('book')
            ->where('id','in',$book_ids)
            ->column('cate_id');
        if($cate_ids){
            $cate_count = array_count_values($cate_ids);
            $cate_id = array_search(max($cate_count),$cate_count);
            $like_book = model('books')
                ->field(['id','title','picture'])
                ->where('cate_id',$cate_id)
                ->where('online',1)
                ->limit($data_setting['guess']*input('num'),$data_setting['guess'])
                ->select();
        }else{
            $like_book = [];
        }
        if($like_book){
            return $this->success('获取成功','',$like_book);
        }else{
            return $this->error('没有了！');
        }

    }

    /* ============ 小说详情 ============== */
    public function book_detail(){
        $book_id = input('book_id');
        if(empty($book_id)){
            $this->error('非法操作','');
        }
        /*+++++ 小说详情 +++++*/
        $book_model = model('books');
        $book_info = $book_model
                    ->field(['b.id','b.picture','b.title','b.type','b.status','a.name as author_name','c.name as cate_name','b.summary'])
                    ->alias('b')
                    ->join('author a','b.author_id = a.id','left')
                    ->join('book_cate c','b.cate_id = c.id','left')
                    ->where('b.id',$book_id)
                    ->find();
        $datas['book_info'] = $book_info;
        /*+++++ 阅读数量 +++++*/
        $reader_count = db('reader_readlast')
                    ->where('book_id',$book_id)
                    ->count();
        $datas['reader_count'] = $reader_count;
        /*+++++ 最近更新 +++++*/
        $update_content = db('book_content')
            ->field(['order_num','name','updated_at'])
            ->where('book_id',$book_id)
            ->find();
        $datas['update_content'] = $update_content;
        /*+++++ 是否阅读与阅读章节位置(阅读地址) +++++*/
        $reader_id = $this->reader['id'];
        $readerreadlast = model('Readerreadlast')
                  ->field(['content_id'])
                  ->where('book_id',$book_id)
                  ->where('reader_id',$reader_id)
                  ->find();
       if($readerreadlast){
           $bookcontent_id = $readerreadlast->content_id;
           $url_type = 1;
       }else{
           $bookcontent_id = '';
           $url_type = 0;
       }
       $datas['bookcontent_id'] = $bookcontent_id;
       $datas['url_type'] = $url_type;
        /*+++++ 猜你喜欢 +++++*/
        $cate_id = db('book')
            ->fetchSql(true)
            ->where('id',$book_id)
            ->column('cate_id');
        $like_book = model('books')
            ->field(['id','title','picture'])
            ->where('cate_id',$cate_id[0])
            ->where('online',1)
            ->limit(3)
            ->select();
        $datas['like_book'] = $like_book;
        $this->assign($datas);
        return view();
    }

    /* ============ 书籍详情（换一批） ============== */
    public function replace_books(){
      $book_id = input('book_id');
        /*+++++ 本书籍相同类型书籍(换一批) +++++*/
        $cate_id = db('book')
            ->where('id',$book_id)
            ->column('cate_id');
        $like_book = model('books')
            ->field(['id','title','picture'])
            ->where('cate_id',$cate_id[0])
            ->where('online',1)
            ->limit(3*input('num'),3)
            ->select();
        $datas['like_book'] = $like_book;
        if($like_book){
            return $this->success('获取成功','',$like_book);
        }else{
            return $this->error('没有了！');
        }

    }

    /* ============ 小说目录 ============== */
    public function content_list(){
        $book_id = input('book_id');
        if(empty($book_id)){
            $this->error('非法操作','');
        }
        /*+++++ 小说名称 +++++*/
        $book_name = model('books')
            ->field('title')
            ->where('id',$book_id)
            ->find();
        $datas['book_name'] = $book_name;
        /*+++++ 小说目录列表 +++++*/
        $book_content_list = model('Bookcontents')
                        ->field(['b.status as book_status'])
                        ->alias('c')
                        ->join('book b','c.book_id = b.id','left')
                        ->where('c.book_id',$book_id)
                        ->select();
        $content_count = count($book_content_list);
        $datas['book_content_list'] = $book_content_list;
        $datas['content_count'] = $content_count;
        $this->assign($datas);
        return view();

    }

    /* ============ 小说目录滑动加载 ============== */
    public function content_lists(){
        $book_id = input('book_id');
        if(empty($book_id)){
            $this->error('数据异常','');
        }
        /*+++++ 小说目录列表 +++++*/
        $bookcontent_list = model('Bookcontents')
            ->field(['order_num','name','price'])
            ->where('book_id',$book_id)
            ->select();
        if($bookcontent_list){
            $this->success('加载成功','',$bookcontent_list);
        }else{
            $this->error('暂无数据','');
        }

    }

    /* ============ 搜索列表页 ============== */
    public function search_list(){
        /*+++++ 搜索小说 +++++*/
        $book_model = model('books');
        $title = input('title');
        $datas['title'] = $title;
        $where['title'] = array('LIKE',"%$title%");
        $where['online'] = 1;
        $book_list = $book_model
            ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
            ->alias('b')
            ->join('author a','b.author_id = a.id','left')
            ->where($where)
            ->select();
        $datas['book_list'] = $book_list;
        /*+++++ 搜索结果数量 +++++*/
        $search_count = count($book_list);
        $datas['search_count'] = $search_count;
        /*+++++ 猜你喜欢 +++++*/
        $reader_id = $this->reader['id'];
        $book_ids = db('reader_bookshelf')
            ->where('reader_id',$reader_id)
            ->column('book_id');
        $cate_ids = db('book')
            ->where('id','in',$book_ids)
            ->column('cate_id');
        if($cate_ids){
            $cate_count = array_count_values($cate_ids);
            $cate_id = array_search(max($cate_count),$cate_count);
            $like_book = model('books')
                ->field(['b.id','b.picture','b.title','a.name as author_name','b.summary'])
                ->alias('b')
                ->join('author a','b.author_id = a.id','left')
                ->where('cate_id',$cate_id)
                ->where('online',1)
                ->select();
        }else{
            $like_book = [];
        }
        $datas['like_book'] = $like_book;
        $this->assign($datas);
        return view();
    }

    /* ============ 小说收费 ============== */
    public function bookstatus(){
        /*+++++ 小说是否存在 +++++*/
        $book_id = input('book_id');
        if(empty($book_id)){
            $this->error('非法操作','');
        }
        /*+++++ 章节号是否存在(存在就取出小说内容表ID)【是否通过点击开始阅读进入】 +++++*/
        $order_num = input('order_num');
        if($order_num){
            $content_id = db('book_content')
                ->where('book_id',$book_id)
                ->where('order_num',$order_num)
                ->column('id');
        }else{
            $content_id = input('content_id');
        }
        /*---------查询章节信息-------*/
        if($order_num){
            $bookcontent_price = db('book_content')
                ->field(['id','price'])
                ->where('book_id',$book_id)
                ->where('order_num',$order_num)
                ->find();
        }else{
            $bookcontent_price = db('book_content')
                ->field(['id','price'])
                ->where('book_id',$book_id)
                ->where('order_num',1)
                ->find();
        }
        /*+++++ 当前账号是否为会员 +++++*/
                $reader_id = $this->reader['id'];

        $reader_vip = model('Readers')
            ->where('id',$reader_id)
            ->column('vip_end');
        $now_time = strtotime(date('Y-m-d H:i:s'));
        /*---------查询小说免费状态与免费限时时间-------*/
        $free_status = model('books')
            ->field(['free_status','free_start','free_end'])
            ->where('id',$book_id)
            ->find();
        /*+++++ 当前账号不为会员或者会员过期,就执行扣费 +++++*/
        if(empty($reader_vip)||$reader_vip[0]<$now_time){
            /*---------++++++当小说为 限时免费时++++++-------*/
            if($free_status['free_status']==1){
                /*+++++ 今天是否限时免费 +++++*/
                $freestart = strtotime(date('Y-m-d')." 00:00:00");
                $freeend = strtotime(date('Y-m-d')." 23:59:59");
                $time_free = model('books')
                    ->where('free_start','<=',$freestart)
                    ->where('free_end','>=',$freeend)
                    ->where('id',$book_id)
                    ->count();
                /*+++++ 如果超过限时免费时间,开始扣费 +++++*/
                if($time_free==0){
                    /*---------如果为收费章节-------*/
                    if($bookcontent_price['price']!=0){
                        $buy_content = db('buy_content')
                                    ->where('book_id',$book_id)
                                    ->where('reader_id',$reader_id)
                                    ->where('content_id',$bookcontent_price['id'])
                                    ->count();
                        if($buy_content==0){
                            $book_money =db('reader')
                                ->field(['book_money'])
                                ->where('id',$reader_id)
                                ->find();
                            if($book_money['book_money']>=$bookcontent_price['price']){
                               /*---开启事务---*/
                                Db::startTrans();
                                try{
                                    /*---------查询章节价格-------*/
                                    if($order_num){
                                        $bookcontent_price = db('book_content')
                                            ->field(['id','price'])
                                            ->where('book_id',$book_id)
                                            ->where('order_num',$order_num)
                                            ->find();
                                    }else{
                                        $bookcontent_price = db('book_content')
                                            ->field(['id','price'])
                                            ->where('book_id',$book_id)
                                            ->where('order_num',1)
                                            ->find();
                                    }
                                    $book_model = new Books;
                                    $bookcontent_model = new Bookcontents;
                                    /*---扣费---*/
                                    model('Readers')->save(['book_money'=>$book_money['book_money']-$bookcontent_price['price']],['id'=>$reader_id]);
                                    /*--- 添加购买章节 ---*/
                                    model('Buycontents')->save(['book_id'=>$book_id,'reader_id'=>$reader_id,'content_id'=>$bookcontent_price['id']]);
                                    /*+++ 查询书籍信息 +++*/
                                    $book_info = $book_model->field(['click_num','buy_num'])->where('id',$book_id)->find();
                                    $bookcontent_info = $bookcontent_model->field(['click_num','buy_num'])->where('id',$bookcontent_price['id'])->find();
                                    /*--- 修改购买量 ---*/
                                    $book_model->save(['buy_num'=>$book_info['buy_num']+1],['id'=>$book_id]);
                                    $rs = $bookcontent_model->save(['buy_num'=>$bookcontent_info['buy_num']+1],['id'=>$bookcontent_price['id']]);
                                    Db::commit();
                                } catch (\Exception $e) {
                                    $rs = '';
                                    Db::rollback();
                                }
                                if(!$rs){
                                    return $this->error('数据错误','');
                                }
                            }else{
                                return $this->error('余额不足,自动支付失败！','');
                            }
                        }
                    }
                }
            }
            /*---------++++++当小说为 收费时++++++-------*/
            if($free_status['free_status']==0){
                /*---------账户扣费-------*/
                if($bookcontent_price['price']!=0){
                    $buy_content = db('buy_content')
                        ->where('book_id',$book_id)
                        ->where('reader_id',$reader_id)
                        ->where('content_id',$bookcontent_price['id'])
                        ->count();
                    if($buy_content==0) {
                        $book_money = db('reader')
                            ->field(['book_money'])
                            ->where('id', $reader_id)
                            ->find();
                        if ($book_money['book_money'] >= $bookcontent_price['price']) {
                            /*---开启事务---*/
                            Db::startTrans();
                            try{
                                /*---------查询章节价格-------*/
                                if($order_num){
                                    $bookcontent_price = db('book_content')
                                        ->field(['id','price'])
                                        ->where('book_id',$book_id)
                                        ->where('order_num',$order_num)
                                        ->find();
                                }else{
                                    $bookcontent_price = db('book_content')
                                        ->field(['id','price'])
                                        ->where('book_id',$book_id)
                                        ->where('order_num',1)
                                        ->find();
                                }
                                $book_model = new Books;
                                $bookcontent_model = new Bookcontents;
                                /*---扣费---*/
                                model('Readers')->save(['book_money'=>$book_money['book_money']-$bookcontent_price['price']],['id'=>$reader_id]);
                                /*--- 添加购买章节 ---*/
                                model('Buycontents')->save(['book_id'=>$book_id,'reader_id'=>$reader_id,'content_id'=>$bookcontent_price['id']]);
                                /*+++ 查询书籍信息 +++*/
                                $book_info = $book_model->field(['click_num','buy_num'])->where('id',$book_id)->find();
                                $bookcontent_info = $bookcontent_model->field(['click_num','buy_num'])->where('id',$bookcontent_price['id'])->find();
                                /*--- 修改购买量 ---*/
                                $book_model->save(['buy_num'=>$book_info['buy_num']+1],['id'=>$book_id]);
                                $rs = $bookcontent_model->save(['buy_num'=>$bookcontent_info['buy_num']+1],['id'=>$bookcontent_price['id']]);
                                Db::commit();
                            } catch (\Exception $e) {
                                $rs = '';
                                Db::rollback();
                            }
                            if(!$rs){
                                return $this->error('数据错误','');
                            }
                        } else {
                            return $this->error('余额不足,自动支付失败！', '');
                        }
                    }
                }
            }
        }
        /*+++ 查询书籍信息 +++*/
        $book_infos = model('Books')->field(['click_num','buy_num'])->where('id',$book_id)->find();
        $bookcontent_infos = model('Bookcontents')->field(['click_num','buy_num'])->where('id',$bookcontent_price['id'])->find();
        /*--- 修改点击量 ---*/
        model('Books')->save(['click_num'=>$book_infos['click_num']+1],['id'=>$book_id]);
        model('Bookcontents')->save(['click_num'=>$bookcontent_infos['click_num']+1],['id'=>$bookcontent_price['id']]);


        /*+++++ 查询是否有数据(最近阅读表) +++++*/
        $readerreadlast = model('Readerreadlast')
            ->field('content_id')
            ->where('book_id',$book_id)
            ->where('reader_id',$reader_id)
            ->find();
        if($readerreadlast){
            /*+++++ 如果阅读过该小说，就修改章节(最近阅读表) +++++*/
            if($content_id){
                  $save_content_id = model('Readerreadlast')->save(['content_id'=>$bookcontent_price['id'],'read_at'=>time()],['book_id'=>$book_id,'reader_id'=>$reader_id]);
            }else{
                $bookcontent_id = db('book_content')
                    ->where('book_id',$book_id)
                    ->where('order_num','1')
                    ->column('id');
                if($bookcontent_id){
                    $save_content_id = model('Readerreadlast')->save(['content_id'=>$bookcontent_id[0],'read_at'=>time()],['book_id'=>$book_id,'reader_id'=>$reader_id]);
                }else{
                    $save_content_id = model('Readerreadlast')->save(['read_at'=>time()],['book_id'=>$book_id,'reader_id'=>$reader_id]);
                }
              }
        }else{
            /*+++++ 如果没阅读过该小说，就添加数据(最近阅读表) +++++*/
            $save_content_id = model('Readerreadlast')->save(['content_id'=>$bookcontent_price['id'],'book_id'=>$book_id,'reader_id'=>$reader_id,'read_at'=>time()]);
        }

        if(!$save_content_id){
            return $this->error('数据错误','');
        }
        return $this->success('加载成功',url('index/book_contents'),$bookcontent_price['id']);
    }

    /* ============ 小说内容 ============== */
    public function book_contents(){
        $id = input('id');
        $content_info = db('book_content')
            ->field('id,book_id,order_num,name,content')
            ->where('id',$id)
            ->find();
        $this->assign('content_info',$content_info);
        $content_name = model('Bookcontents')
            ->field('id,book_id,order_num,name')
            ->where('book_id',$content_info['book_id'])
            ->select();
       $this->assign('content_name',$content_name);
        return view();
    }

    /* ============ 添加书签 ============== */
    public function add_flag(){
        $book_id = input('book_id');
        $order_num = input('order_num');
        if(empty($order_num)){
            $this->error('无内容可添加','');
        }
        $bookcontent_id = db('book_content')
            ->field(['id'])
            ->where('book_id',$book_id)
            ->where('order_num',$order_num)
            ->find();
                $reader_id = $this->reader['id'];

        $save_flag = model('Readerbookmark')->save(['content_id'=>$bookcontent_id['id'],'book_id'=>$book_id,'reader_id'=>$reader_id,'read_at'=>time()]);
        if($save_flag){
            return $this->success('添加书签成功','');
        }else{
            return $this->error('添加书签失败','');
        }

    }
}
