//滚动时出现回顶按钮
$(window).scroll(function() {
    if($(document).scrollTop() > $(window).height()) {
        $(".toTop").show(300);
    } else {
        $(".toTop").fadeOut(300);
    }
});
$(".toTop").click(function(){
    $("body,html").animate({scrollTop: 0},600);
});
//+++++++++++++++++++++++++分类列表页面中的js++++++++++++++++++++++++++++++++++++++++
function classiFication() {
    $("#listCon>span:gt(3)").css("display", "none");
    $("#openlist").click(function() {
        $("#listCon>span:gt(3)").toggle();
        $(this).children("i").toggleClass("icon-iconfanning");
        if($(this).children("i").hasClass("icon-iconfanning")){
            $(this).children("span").text("展开");
            $(this).children("i").removeClass("icon-iconputaway")
        }else{
            $(this).children("span").text("收起");
            $(this).children("i").addClass("icon-iconputaway")
        }
    });
    $(".listCon>span").click(function() {
        $(this).addClass("on").siblings("span").removeClass("on");
    })
}
//++++++++++++++++++++++++++++++++首页++++++++++++++++++++++++++++++++
function banner() {
    //+++++++++++banner滑动+++++++++++++++++++
    // 获取手指在轮播图元素上的一个滑动方向（左右）

    // 获取界面上轮播图容器
    var $carousels = $('.carousel');
    var startX, endX;
    // 在滑动的一定范围内，才切换图片
    var offset = 50;
    // 注册滑动事件
    $carousels.on('touchstart', function(e) {
        // 手指触摸开始时记录一下手指所在的坐标x
        startX = e.originalEvent.touches[0].clientX;

    });
    $carousels.on('touchmove', function(e) {
        // 目的是：记录手指离开屏幕一瞬间的位置 ，用move事件重复赋值
        endX = e.originalEvent.touches[0].clientX;
    });
    $carousels.on('touchend', function(e) {
        //console.log(endX);
        //结束触摸一瞬间记录手指最后所在坐标x的位置 endX
        //比较endX与startX的大小，并获取每次运动的距离，当距离大于一定值时认为是有方向的变化
        var distance = Math.abs(startX - endX);
        if(distance > offset) {
            //说明有方向的变化
            //根据获得的方向 判断是上一张还是下一张出现
            $(this).carousel(startX > endX ? 'next' : 'prev');
        }
    })
    //  daojishi();
}

function indexjs() {
    banner();

}

function recharge() {
    $(".topdiv").each(function() {
        if($(this).children('p.topP').html() == '' || $(this).children('p.topP').html() == undefined) {
            $(this).children("p.topBi").css("padding-top", "30px")
        }
    });
    $(".recharge>div>div").click(function() {
        $(this).children("div").addClass("on");
        $(this).parent("div").siblings().children("div").children("div").removeClass("on")
    })
}

//**************活动结束倒计时*****************************
function ShowCountDown(year, month, day, divname) {
    console.log(month);
    var now = new Date();
    var endDate = new Date(year, month-1, day);
    var leftTime = endDate.getTime() - now.getTime();
    var dd = parseInt(leftTime / 1000 / 60 / 60 / 24, 10); //计算剩余的天数
    var hh = parseInt(leftTime / 1000 / 60 / 60 % 24, 10); //计算剩余的小时数
    var mm = parseInt(leftTime / 1000 / 60 % 60, 10); //计算剩余的分钟数
    var ss = parseInt(leftTime / 1000 % 60, 10); //计算剩余的秒数
//	dd = checkTime(dd);
    hh = checkTime(hh);
    mm = checkTime(mm);
    ss = checkTime(ss); //小于10的话加0
    var cc = document.getElementById(divname);
    if(dd <= 0){
        $(".day").css("display","none");
    }else{
        $(".day").css("display","inline-block");
        $(".day").text(dd+"天  ");
    }

    $(".hour").text(hh);
    $(".minute").text(mm);
    $(".second").text(ss);
}

function checkTime(i) {
    if(i < 10) {
        i = "0" + i;
    }
    return i;
}

//******************目录页*********************
function directoryList() {
    var win_h = $(window).height();
    var num = 1;
    getMores(num);
    $(document).scroll(function(e) {
        var scr_h = $(window).scrollTop();
        var body_h = $("body").height();
        if(body_h - win_h - scr_h < 10) {
            num++;
            getMores(num);
        }
    });

}

function getMores(num) {
    $.ajax({
        url: mulu_url,
        data: { 'book_id':bookid},
        type:'POST',
        dataType:'JSON',
        success: function(data) {
            for(var i = 20 * (num - 1); i < (20 * num > data.data.length ? data.data.length : 20 * num); i++) {
                if(data.data[i]['price']==0){
                    var img_zs = '';
                }else{
                    img_zs = '<i class="iconfont icon-zuanshi"></i>';
                }
                $("#muList").append('<div class="col-xs-12"  onclick="readbooks(this)" id="a1"><input type="hidden" name="ordernum" class="ordernum" value="'+data.data[i]['order_num']+'"> <span>第'+data.data[i]['order_num']+'章</span><span>'+data.data[i]['name']+'</span>'+img_zs+'</div>');
            }
        },
        error:function () {
            layer.msg('数据错误');
        }
    });
}
//*************************我的书架************************
function bookShelf(){
    $("[la]").click(function(){
        $(this).addClass("on").siblings().removeClass("on");
        $("[lay]").css("display","none");
        $("[lay="+$(this).attr('la')+"]").css("display",'block');
    });

    //编辑
    $("#edit").click(function(){
        $(this).css("display","none");
        $(".delBtn,.cancelBtn").css("display","inline-block");
        $(".inputDiv").css("display","inline-block");

    });

    //完成
    $("#cancelBtn").click(function(){
        $("#edit").css("display","inline-block");
        $(".delBtn,.cancelBtn").css("display","none");
        $(".inputDiv").css("display","none");
    })

}





//**************************图书内容页面*********************************
function content(){
    if($.cookie('numn_'+book_id+'_'+reader_id+'_'+order_nums)){
        var numn = $.cookie('numn_'+book_id+'_'+reader_id+'_'+order_nums);
    }else{
        var numn=0;
    }
    var viewH=$(window).height();
    $("#content").css("width",$(window).width()+"px");
    $("#content").css({"height":(Math.floor(viewH / 24)-2)*24+"px","margin-top":"36px"});
    $("#content").css("width",$(window).width()*$("#content>div").length+"px");

    var leng = Math.ceil($("#content>div").height()/$("#content").height());
    if($.cookie('numn_'+book_id+'_'+reader_id+'_'+order_nums)){
        if(numn == 1) {
            $(".layui-layer-msg").css("display", "none");
        }
        $("#content>div").css({"left":$(window).width()+"px","top":-$("#content").height()*numn+"px"});
        $("#content>div").animate({"left":"0px"},600);
    }
    //右翻
    $(".conRight").click(function() {
        numn++;
        $.cookie('numn_'+book_id+'_'+reader_id+'_'+order_nums,numn);
        if(numn >= leng){
            layer.msg("努力加载中...");
            numn = leng-1;
            $.cookie('numn_'+book_id+'_'+reader_id+'_'+order_nums,numn);
            $("#content>div").css("top",-$("#content").height()*numn+"px");
            var order_num  = parseInt(order_nums)+1;
            var data = {
                'order_num':order_num,
                'book_id':book_id
            };
            $.ajax({
                url:order_nums_url,
                data:data,
                type:'POST',
                dataType:'JSON',
                success:function (data) {
                    console.log(data);
                    if(data.code==1){
                        layer.msg(data.msg);
                        location.href=data.url+"?id="+data.data;
                    }
                    if(data.code==0){
                        layer.msg(data.msg);
                    }
                },
                error:function () {
                    layer.msg('数据错误');
                }
            })
        }else{
            if(numn == 1) {
                $(".layui-layer-msg").css("display", "none");
            }
            $("#content>div").css({"left":$(window).width()+"px","top":-$("#content").height()*numn+"px"});
            $("#content>div").animate({"left":"0px"},600)
        }
        return numn;
    });
    //左翻
    $(".conLeft").click(function() {
        numn--;
        console.log(numn);
        $.cookie('numn_'+book_id+'_'+reader_id+'_'+order_nums,numn);
        if(numn < 0){
            $.cookie('numn_'+book_id+'_'+reader_id+'_'+order_nums,0);
            if(order_nums==1){
                layer.msg("已是第一页");
                numn = 0;
                $("#content>div").css({"left":"0px","top":-$("#content").height()*numn+"px"});
                return false;
            }else{
                layer.msg("努力加载中...");
                $("#content>div").css("top",-$("#content").height()*(numn-1)+"px");
                var order_num  = parseInt(order_nums)-1;
                var data = {
                    'order_num':order_num,
                    'book_id':book_id
                };
                $.ajax({
                    url:order_nums_url,
                    data:data,
                    type:'POST',
                    dataType:'JSON',
                    success:function (data) {
                        console.log(data);
                        if(data.code==1){
                            layer.msg(data.msg);
                            location.href=data.url+"?id="+data.data;
                        }
                        if(data.code==0){
                            layer.msg(data.msg);
                        }
                    },
                    error:function () {
                        layer.msg('数据错误');
                    }
                })
            }

        }else{
            if(numn == leng-2){
                $(".layui-layer-msg").css("display", "none");
            }
            $("#content>div").css({"left":-$(window).width()+"px","top":-$("#content").height()*numn+"px"});
            $("#content>div").animate({"left":"0px"},600);
        }

    });

    //点击中间出现 头部和底部
    $(".conCenter").click(function(){
        $(".conFooter,.conHead").slideToggle(300);
    });

    //li背景
    $(".conList li").click(function(){
        $(this).addClass("on").siblings().removeClass("on");
    });
    //打开目录
    $(".openList").click(function(){
        $(".contentList").fadeIn();
    });

    $("[lm]").click(function(){
        $(".contentList").fadeOut();
        $(".conFooter,.conHead").slideToggle(300);
    });


    if($.cookie('moshi')){
        $("body").attr('class',$.cookie('moshi'));
        if($.cookie('moshi')=='moshi1'){
            $(".rijian").attr('style','display:block');
            $(".yejian").attr('style','display:none');
        }else{
            $(".rijian").attr('style','display:block');
            $(".yejian").attr('style','display:none');
        }
    }
    //日间和夜间模式切换
    $("[lb]").click(function(){
        $(this).css("display","none").siblings("[lb]").css("display","block");
        $("body").addClass("moshi"+$(this).attr("lb")).removeClass("moshi"+$(this).siblings("[lb]").attr("lb"));
        $(".conFooter,.conHead").slideToggle(300);
        var display =$('.rijian').css('display');
        if(display == 'none'){
           $.cookie('moshi','moshi2');
        }else{
            $.cookie('moshi','moshi1');
        }
    });

    //添加标签
    $(".addFlag").click(function(){
        // layer.msg("添加书签成功");
        var data = {
            'order_num':order_nums,
            'book_id':book_id
        };
        $.ajax({
            url:addFlags_url,
            data:data,
            type:'POST',
            dataType:'JSON',
            success:function (data) {
                console.log(data);
                if(data.code==1){
                    layer.msg(data.msg);
                }
                if(data.code==0){
                    layer.msg(data.msg);
                }
            },
            error:function () {
                layer.msg('数据错误');
            }
        })
    })
}
//****************************个人中心**************************************
function mine(){
    $("#vip").click(function(){
        console.log(1);
        $(".openVip").fadeIn(200,function(){
            $(".openVip>div").slideDown(400);
        });
    });
    $(".closeOPen").click(function(){
        $(".openVip").fadeOut(200);
        $(".openVip>div").css("display","none")
    })

}

//************************搜索列表***************************888
function searchList(){

    $("#serInput").keydown(function(){
        if($("#serInput").val() == ''){
            $("#serBtn").text("取消");
        }else{
            $("#serBtn").text("搜索");
        }
    })
}










