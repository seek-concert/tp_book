<?php
/* |------------------------------------------------------
 * | 小说书架
 * |------------------------------------------------------
 * |
 * | 我的书架
 * | 最近阅读
 * | 我的书签
 * */

namespace app\index\controller;


class Bookshelf extends Auth
{
    /* ============ 小说书架 ============== */
    public function index(){
        $reader_id = $this->reader['id'];
        $reader_id = 1;
        /*+++++ 我的书架 +++++*/
        $bookshelf_list = model('Readbookshelf')
            ->field(['b.id','b.picture','b.title','b.status','a.name as author_name','s.content_id','c.order_num','c.name','d.order_num as order_nums'])
            ->alias('s')
            ->join('book b','s.book_id = b.id','left')
            ->join('author a','b.author_id = a.id','left')
            ->join('book_content c','b.edited_at = c.edited_at','left')
            ->join('book_content d','s.content_id = d.id','left')
            ->where('reader_id',$reader_id)
            ->select();
        $datas['bookshelf_list'] = $bookshelf_list;
        $bookshelf_count =count($bookshelf_list);
        $datas['bookshelf_count'] = $bookshelf_count;
        /*+++++ 最近阅读 +++++*/
        $readerreadlast_list = model('Readerreadlast')
            ->field(['b.id','b.picture','b.title','b.status','a.name as author_name','s.content_id','c.order_num','c.name','d.order_num as order_nums'])
            ->alias('s')
            ->join('book b','s.book_id = b.id','left')
            ->join('author a','b.author_id = a.id','left')
            ->join('book_content c','b.edited_at = c.edited_at','left')
            ->join('book_content d','s.content_id = d.id','left')
            ->where('reader_id',$reader_id)
            ->select();
        $datas['readerreadlast_list'] = $readerreadlast_list;

        $this->assign($datas);
        return view();
    }
}