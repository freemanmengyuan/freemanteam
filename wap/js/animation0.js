/**
 * Created by Administrator on 2016/4/6.
 */
$(document).ready(function(){

    $("body").on("tap",".curseList>li",function () {
        var $chapters = $(this).children(".chapters");
        if($chapters.has("li").length > 0){

        }
        else{
            alert({
                title:"提示",
                content:"该章下没有节",
                canel:function () {
                    $("#dialog2").remove();
                }
            });
            return false;
        }
        $(".curseList").css("white-space","inherit");
        $(this).children(".chapters").toggleClass("animation1")
        $(this).children(".chapters").toggle()
       // console.log(this)
        $(".chapters>li").children("img").removeClass("animationleve animation2");
        $(".points").hide();/* 初始化二级菜单*/
        setTimeout("$('.curseList').css('white-space','nowrap')",200); /* 处理uc浏览器 对white-space 兼容问题*/
    })
    $("body").on("tap",".chapters>li",function (e) {

        e.stopPropagation();    //阻止事件冒泡
        var $scList = $(this).children(".points");
        if($scList.has("li").length > 0){
        }else{
            alert({
                title:"提示",
                content:"该节下没有知识点",
                canel:function () {
                    $("#dialog2").remove();
                }
            });
            return false;
        }
        var $images = $(this).children("img:first");
        //console.log(this)
        if($images.hasClass("animation2")){
            $images.removeClass("animation2")
            $images.addClass("animationleve")
        }else{
            if($images.hasClass("animationleve")){
                $images.removeClass("animationleve")
            }
            $images.addClass("animation2")
        }
//            $(this).children("img").toggleClass("animation2 animationleve")
        $(this).children(".points").toggle()
    })
    $("body").on("tap",".points li",function (e) {
        e.stopPropagation();
        var $scList = $(this).children(".scList");
        if($scList.has("a").length > 0){
            $(this).children(".scList").toggle();
        }else{
            alert({
                title:"提示",
                content:"没有素材",
                canel:function () {
                    $("#dialog2").remove();
                }
            })
        }

    })
    $("body").on("tap",".scList a",function (e) {
        e.stopPropagation();
        var datasrc = $(this).attr("data-src");
        var datatitle =$(this).attr("data-title")
        $('iframe').attr("src",datasrc);
//        console.log(datasrc)
        $('.ifram').show();

        $('.ifram h2').html(datatitle)
        $("body").addClass("open")
    })
    $(".ifram img").tap(function () {
        $(".ifram").hide();
        $("body").removeClass("open")
    })
});
