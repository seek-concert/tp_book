define("readnovelm/js/common/page/index.0.25.js",["lib.Zepto","util.ejs2","util.Cookie","xiaoyue.login","qidian.report","readnovelm/js/common/utils/debounce.0.7.js","readnovelm/js/common/components/Toggle.0.7.js","readnovelm/js/common/components/Tips.0.9.js","readnovelm/js/common/components/Loading.0.8.js","readnovelm/js/common/store/searchHistory.0.7.js"],function(require,exports,module){var t=require("lib.Zepto"),o=require("util.ejs2"),e=require("util.Cookie"),i=require("xiaoyue.login"),r=require("qidian.report"),n=require("readnovelm/js/common/utils/debounce.0.7.js"),a=require("readnovelm/js/common/components/Toggle.0.7.js");require("readnovelm/js/common/components/Tips.0.9.js"),require("readnovelm/js/common/components/Loading.0.8.js");var c=require("readnovelm/js/common/store/searchHistory.0.7.js");module.exports={init:function(){this.initGlobalAjaxSetting(),this.initGlobalLogin(),this.initHeader(),this.initFooter(),r.config({cgi:"//"+g_data.domains.commonReport+"/qreport"}),r.init({path:"subweb",cname:"XYmlog"}),t("#aGoPCSite").on("click",function(){e.set("tf",1,"readnovel.com","/")})},initGlobalAjaxSetting:function(){var o=this,i={dataType:"json"},r=e.get("_csrfToken");r&&(i.data={_csrfToken:r});var n=location.protocol?location.protocol:"https:";t.ajaxSettings=t.extend(!0,t.ajaxSettings,i),t(document).on("ajaxError",function(e,i,r){401===i.status?o.gotoLogin({returnurl:n+"//"+g_data.domainPrefix+"m.readnovel.com/loginSuccess?jumpUrl="+encodeURIComponent(location.href)}):t.isFunction(r.unexpectError)?r.unexpectError.call(r.context,i,r):r.noErrorTips||t.tips("网络异常，请稍后重试"),t.unloading(),t(".loading").removeClass("loading")}),t.getJSONSlient=function(o,e,i){return t.isFunction(e)&&(i=e,e=void 0),t.ajax({type:"get",url:o,data:e,success:i,noErrorTips:!0})},t.postJSON=function(o,e,i){return r&&(o+=(o.indexOf("?")>-1?"&":"?")+"_csrfToken="+r),t.ajax({type:"POST",url:o,contentType:"application/json",data:JSON.stringify(e),success:i})},t.postSlient=function(o,e,i){return t.isFunction(e)&&(i=e,e=void 0),t.ajax({type:"post",url:o,data:e,success:i,noErrorTips:!0})}},initGlobalLogin:function(){var o=this;i.init(!0),i.setCallback("success",function(){location.href=location.href.split("#")[0]}),t("a.jsLogin").removeClass(".jsLogin").on("click",function(){var e=t(this),i=e.prop("href").indexOf("http")>-1?e.prop("href"):"",r=location.protocol?location.protocol:"https:";return o.gotoLogin({returnurl:r+"//"+g_data.domainPrefix+"m.readnovel.com/loginSuccess?jumpUrl="+encodeURIComponent(i)}),!1})},gotoLogin:function(t){t||(t={});var o=t.returnurl||location.href;o.indexOf("?")>-1?o+="&from=login":o+="?from=login",t.returnurl=o,location.href=i.getMLoginUrl(t)},initHeader:function(){var o,e=this,i=t(".jsBack").on("click",function(){if(/^javas/.test(this.href))return history.go(-1),!1});t("#openSearchPopup").on("click",function(){var i=t(this).data("keyword")||"";return t("#searchPopup").removeAttr("hidden"),t("#keyword").attr("placeholder",i).focus(),o||(o=!0,e.initSearch()),!1});var r;t("#newOpen").on("click",function(){var o=t(this).data("keyword")||"",i=t(this).data("id");return t("#listsearchPopup").removeAttr("hidden"),t("#listkeyword").attr("placeholder",o).focus(),t("#listId").val(i),r||(r=!0,e.initlistSearch(i)),!1}),new a("#openGuide",function(t){var o=t.attr("title"),i=t.parents("header");/收起/.test(o)?(t.attr("title",o.replace("收起","")),i.removeAttr("open")):(i.attr("open",""),t.attr("title","收起"+o),e.elBacktop&&e.elBacktop.trigger("click"))}),t("#guideOverlay").on("click",function(){t("#openGuide").trigger("click")});var n=t("#guide").on("touchmove",function(t){t.preventDefault()});n.length&&(window.addEventListener("resize",function(){e.elBacktop&&n.hasClass("active")&&e.elBacktop.trigger("click")}),t("#header").on("touchmove",function(t){n.hasClass("active")&&t.preventDefault()})),t("#header nav a").on("click",function(t){var o=this.href;!1===/^#/.test(o)&&!1===/^javasc/.test(o)&&(t.preventDefault(),history.replaceState(null,document.title,o.split("#")[0]+"#"),location.replace(""))}).each(function(){var o=t(this);o.parent("h3").attr({role:"navigation",title:o.hasClass("active")?"已选择":""})});var c=document.referrer;"string"==typeof c&&""===c&&i.attr("href","/")},initFooter:function(){var o=t(window),e=t(".jsBackToTop").on("click",function(){var t=o.scrollTop(),e=function(){t*=.5,t<1?o.scrollTop(0):(o.scrollTop(t),requestAnimationFrame(e))};return e(),!1});this.elBacktop=e;var i=t("#footerApp");o.on("scroll",function(){var t=o.scrollTop(),r=o.height();if(t>r&&(!i.length||t+r-i.position().top<=0))return void e.css({opacity:1,visibility:"visible"});e.css({opacity:0,visibility:"hidden"})});var r=navigator.userAgent&&/\bAndroid ([\d\.]+)/.test(navigator.userAgent);t(".jsDownloadLink").each(function(){var o=t(this);/^javas/.test(o.attr("href"))&&(o.attr("href",r?o.data("android"):o.data("ios")),o.removeAttr("data-android"),o.removeAttr("data-ios"))})},initSearch:function(){t("#closeSearchPopup").on("click",function(){t("#searchPopup").attr("hidden",""),t("body").removeClass("open")}),this.initSearchForm(),this.initSearchPopularWords(),this.initSearchHistory()},initSearchForm:function(){var e=t("#keyword"),i=t("#clearSearchKeyword"),r=t("#searchHotHistory"),a=t("#searchList"),s=e.val(),l=function(){s&&t.getJSON("/majax/search/auto?kw="+s,function(t){if(0===t.code){if(!s)return;r.attr("hidden",""),a.removeAttr("hidden").html(o.render("#tpl-search-list.html",t))}})},d=n(l,300);e.on("input",function(){s=t(this).val().trim(),s?(i.removeAttr("hidden"),d()):(i.attr("hidden",""),r.removeAttr("hidden"),a.attr("hidden",""))}),i.on("click",function(){e.val("").trigger("input").focus()}),s&&(i.removeAttr("hidden"),r.attr("hidden",""),l()),t("#searchPopup").on("click",".jsSearchLink",function(){s=t(this).data("keyword"),c.addKeyword(s),e.val(s)}).on("touchmove",function(t){t.preventDefault()}),t("#searchForm").on("submit",function(){s=e.val().trim(),!s&&e.attr("placeholder")&&(s=e.attr("placeholder"),e.val(s)),c.addKeyword(s)})},initSearchPopularWords:function(){t.getJSON("/majax/search/auto?kw=",function(e){if(0===e.code){var i=o.render("#tpl-search-popular-words.html",e),r=t("#searchPopularWords").html(i);t("#keyword").val()?r.height("auto"):r.unloading(200)}})},initSearchHistory:function(){var e=c.getKeywords(),i=o.render("#tpl-search-history.html",{data:e}),r=t("#searchHistory").html(i);t("#clearSearchHistory").on("click",function(){c.clearKeywords(),r.attr("hidden","")})},initlistSearch:function(o){t("#listcloseSearchPopup").on("click",function(){t("#listsearchPopup").attr("hidden",""),t("body").removeClass("open")}),this.initlistSearchForm(o),this.initlistSearchPopularWords(o),this.initlistSearchHistory(o)},initlistSearchForm:function(){var e=t("#listkeyword"),i=t("#listclearSearchKeyword"),r=t("#listsearchHotHistory"),a=t("#listsearchList"),s=e.val(),l=function(){s&&t.getJSON("/majax/search/auto?kw="+s,function(t){if(0===t.code){if(!s)return;r.attr("hidden",""),a.removeAttr("hidden").html(o.render("#tpl-listsearch-list.html",t))}})},d=n(l,300);e.on("input",function(){s=t(this).val().trim(),s?(i.removeAttr("hidden"),d()):(i.attr("hidden",""),r.removeAttr("hidden"),a.attr("hidden",""))}),i.on("click",function(){e.val("").trigger("input").focus()}),s&&(i.removeAttr("hidden"),r.attr("hidden",""),l()),t("#listsearchPopup").on("click",".jsSearchLink",function(){s=t(this).data("keyword"),c.addKeyword(s),e.val(s)}).on("touchmove",function(t){t.preventDefault()}),t("#listsearchForm").on("submit",function(){s=e.val().trim(),!s&&e.attr("placeholder")&&(s=e.attr("placeholder"),e.val(s)),c.addKeyword(s)})},initlistSearchPopularWords:function(e){t.getJSON("/majax/search/auto?kw=",function(i){if(0===i.code){i.data.id=e;var r=o.render("#tpl-listsearch-popular-words.html",i),n=t("#listsearchPopularWords").html(r);t("#listkeyword").val()?n.height("auto"):n.unloading(200)}})},initlistSearchHistory:function(e){var i=c.getKeywords(),r=o.render("#tpl-listsearch-history.html",{data:[i,e]}),n=t("#listsearchHistory").html(r);t("#listclearSearchHistory").on("click",function(){c.clearKeywords(),n.attr("hidden","")})}}});