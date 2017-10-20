<?php
/* |------------------------------------------------------
 * | 首页
 * |------------------------------------------------------
 * |
 * */

namespace app\index\controller;

class Index extends Auth
{
    public function index()
    {
        $book_model = model('book');
        $data_setting = db('data_setting')->find();
        $datas['data_setting'] = $data_setting;
        /*+++++ 主编推荐 +++++*/
        $is_recommend = $book_model
            ->field('picture,title')
            ->where('is_recommend',1)
            ->where('online',1)
            ->order('sort desc')
            ->limit($data_setting['recommend'])
            ->select();
        $datas['is_recommend'] = $is_recommend;
        /*+++++ 热门小说 +++++*/
        $is_hot = $book_model
            ->field('picture,title,author_id,summary')
            ->where('is_hot',1)
            ->where('online',1)
            ->order('sort desc')
            ->limit($data_setting['hot'])
            ->select();
        $datas['is_hot'] = $is_hot;
        /*+++++ 新书推荐 +++++*/
        $created_at = $book_model
            ->field('picture,title,author_id,summary')
            ->where('online',1)
            ->order('created_at desc,sort asc')
            ->limit($data_setting['new_recommend'])
            ->select();
        $datas['created_at'] = $created_at;
        /*+++++ 限时免费 +++++*/
        $freestart = strtotime(date('Y-m-d')." 00:00:00");
        $freeend = strtotime(date('Y-m-d')." 23:59:59");
        $timelimit = $book_model
            ->field('picture,title')
            ->where('free_start','<=',$freestart)
            ->where('free_end','>=',$freeend)
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
            $like_book = model('book')
                ->where('cate_id',$cate_id)
                ->where('online',1)
                ->limit($data_setting['guess'])
                ->column('id,title,picture');
        }else{
            $like_book = [];
        }
        $datas['like_book'] = $like_book;
        /*+++++ 畅销书单 +++++*/
        $buy_num_book = model('book')
            ->field('id,title,author_id')
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
}
