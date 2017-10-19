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
        /*+++++ 主编推荐 +++++*/
        $is_recommend = $book_model
            ->field('picture,title')
            ->where('is_recommend',1)
            ->order('sort desc')
            ->select();
        $datas['is_recommend'] = $is_recommend;
        /*+++++ 热门小说 +++++*/
        $is_hot = $book_model
            ->field('picture,title,author_id,summary')
            ->where('is_hot',1)
            ->order('sort desc')
            ->select();
        $datas['is_hot'] = $is_hot;
        /*+++++ 新书推荐 +++++*/
        $created_at = $book_model
            ->field('picture,title,author_id,summary')
            ->order('sort desc')
            ->select();
        $datas['is_hot'] = $is_hot;
        return view();
    }
}
