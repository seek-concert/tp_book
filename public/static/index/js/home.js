//滚动时出现回顶按钮
$(window).scroll(function() {
	if($(document).scrollTop() > $(window).height()) {
		$(".toTop").show(300);
	} else {
		$(".toTop").fadeOut(300);
	}
})
//+++++++++++++++++++++++++分类列表页面中的js++++++++++++++++++++++++++++++++++++++++
function classiFication() {
	$("#listCon>span:gt(3)").css("display", "none")
	$("#openlist").click(function() {
		$("#listCon>span:gt(3)").toggle();
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
	var now = new Date();
	var endDate = new Date(year, month, day);
	var leftTime = endDate.getTime() - now.getTime();
	var dd = parseInt(leftTime / 1000 / 60 / 60 / 24, 10); //计算剩余的天数
	var hh = parseInt(leftTime / 1000 / 60 / 60 % 24, 10); //计算剩余的小时数
	var mm = parseInt(leftTime / 1000 / 60 % 60, 10); //计算剩余的分钟数
	var ss = parseInt(leftTime / 1000 % 60, 10); //计算剩余的秒数
	dd = checkTime(dd);
	hh = checkTime(hh);
	mm = checkTime(mm);
	ss = checkTime(ss); //小于10的话加0
	var cc = document.getElementById(divname);
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
	console.log($(window).height());
	console.log($("body").height());
	var win_h = $(window).height();
	var body_h = $("body").height();
	var num = 1;
	getMore(num);
	$(document).scroll(function(e) {
		console.log($(window).scrollTop());
		var scr_h = $(window).scrollTop();
		if(body_h - win_h - scr_h < 10) {
			num++;
			getMore(num);
		}
	});

}

function getMore(num) {
	$.ajax({
		type: "get",
		url: "new_file.json",
		async: true,
		success: function(json) {
			console.log(json);
			for(var i = 12 * (num - 1); i < (12 * num > json.books.length ? json.books.length : 12 * num); i++) {
				$("#muList").append(`<div class="col-xs-12" id="a1">
				<span>${json.books[i].muLu}</span><span>${json.books[i].book}</span><i class="iconfont icon-zuanshi">
			</div>`);
			}
		}
	});
}
//*************************我的书架************************
function bookShelf(){
	$("[la]").click(function(){
		$(this).addClass("on").siblings().removeClass("on");
		$("[lay]").css("display","none")
		$("[lay="+$(this).attr('la')+"]").css("display",'block');
	})
}





//**************************图书内容页面*********************************
function content(){
	var numn=0;
	$("#content").css("width",$(window).width()*$("#content>div").length+"px")
	$("#content>div").css("width",$(window).width()+"px")
	//右翻
	$(".conRight").click(function(){
		numn++;
		if(numn >= $("#content>div").length){
			layer.msg("正在加载为您下一章，请稍等");
			return false;
		}else{
		$("#content>div").eq(numn-1).animate({marginLeft:-$(window).width()+"px"},600);
		}
		return numn;
	})
	//左翻
	$(".conLeft").click(function(){
		console.log(numn)
		if(numn <= 0){
			layer.msg("此处已经是第一章了");
			numn = 0;
			return false;
		}else{
		$("#content>div").eq(numn).animate({marginLeft:0+"px"},function(){
			$("#content>div").eq(numn-1).fadeIn()
		});
		$("#content>div").eq(numn-1).animate({marginLeft:"0px"});
		numn --;
		
		}
	})
	
	
	//点击中间出现 头部和底部	
	$(".conCenter").click(function(){
		$(".conFooter,.conHead").slideToggle(300);
	})
	
	//li背景
	$(".conList li").click(function(){
		$(this).addClass("on").siblings().removeClass("on");
	})
	//打开目录
	$(".openList").click(function(){
		$(".contentList").fadeIn();
	})
	
	$("[lm]").click(function(){
		$(".contentList").fadeOut();
		$(".conFooter,.conHead").slideToggle(300);
	})
	
	//日间和夜间模式切换
	$("[lb]").click(function(){
		$(this).css("display","none").siblings("[lb]").css("display","block");
		$("body").addClass("moshi"+$(this).attr("lb")).removeClass("moshi"+$(this).siblings("[lb]").attr("lb"));
		$(".conFooter,.conHead").slideToggle(300);
	})
	
	//添加标签
	$(".addFlag").click(function(){
		layer.msg("添加书签成功")
	})
}
//****************************个人中心**************************************
function mine(){
	$("#vip").click(function(){
		console.log(1)
		$(".openVip").fadeIn(200,function(){
			$(".openVip>div").slideDown(400);
		});
	})
	$(".closeOPen").click(function(){
		$(".openVip").fadeOut(200);
		$(".openVip>div").css("display","none")
	})

}













