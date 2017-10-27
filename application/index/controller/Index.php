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
 * | 小说内容
 * */

namespace app\index\controller;

class Index extends Auth
{
    /* ============ 首页 ============== */
    public function index()
    {
        $book_model = model('Books');
        $data_setting = db('data_setting')->find();
        $datas['data_setting'] = $data_setting;
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
//        $reader_id = $this->reader['id'];
        $reader_id = 1;
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
//        $reader_id = $this->reader['id'];
        $reader_id = 1;
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
//        $reader_id = $this->reader['id'];
        $reader_id = 1;
        $readerreadlast = model('Readerreadlast')
                  ->field(['content_id'])
                  ->where('book_id',$book_id)
                  ->where('reader_id',$reader_id)
                  ->find();
       if($readerreadlast){
           $bookcontent_url = url("index/book_contents",array('book_id'=>$book_id,'content_id'=>$readerreadlast->content_id));
           $url_type = 1;
       }else{
           $bookcontent_url = url("index/book_contents",array('book_id'=>$book_id));
           $url_type = 0;
       }
       $datas['bookcontent_url'] = $bookcontent_url;
       $datas['url_type'] = $url_type;
        /*+++++ 猜你喜欢 +++++*/
        $cate_id = db('book')
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
        $book_content_list = db('book_content')
                        ->field(['b.status as book_status','c.order_num','c.name','c.price'])
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
//        $reader_id = $this->reader['id'];
        $reader_id = 1;
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

    /* ============ 小说内容 ============== */
    public function book_contents(){
        return view();
    }
}
