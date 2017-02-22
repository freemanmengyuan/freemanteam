/**
 * Created by Administrator on 2016/4/14.
 */
var apiUrl = "/index.php/";
var key = "imkey";
var loginAddress = "../user/login.html";
var AllCourse = "/wap/htmlv/index.html?fun=myindex";//学生默认首页
var courseDefault = "/wap/images/courDefault.png" ;  /*课程默认图片*/
var fontageDafult  ="/wap/images/loading.gif" ;  /*课程默认图片*/
var peosonDefault ="/wap/images/user.png"; /*个人头像*/
var teacherindex = "/wap/htmlv/teacher/index.html?fun=teacherinfo";//教师默认首页
/*初始化方法*/

/*
禁用右上角菜单
*/
function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
var weixinype = isWeiXin();
    if(weixinype){
      
    }else{
      window.location.href="/wap/htmlv/error.html"
    }

// wx.hideOptionMenu();

/*
* iframe内 触发侧滑菜单
* 防止在iframe外调用 才使用try
*/

  $(document).on("pageshow",function(){
    $('body').on('swiperight',function(){
        try{parent.leftPanel()}catch(e){
          //console.log("产生了错误")
        }
    })
  });

/*
* 更改title
*/
$(document).on("pageshow",function(){
  if (self != top) {
    var arr = parent.document.title.split("-");
    if(arr.length>1){
      str = arr[1]
    }else{
      str = parent.document.title
    }
     parent.document.title=document.title+'-'+str;
}
});
/*
* 实现下拉加载更多
*/

/*
* 实现对touch的移动距离判断
*/
var reLoad = function(obj){
  var startX,startY,endX,endY;
  var showADID = 1;
  var id = obj.main;
  var load = obj.load;
  var success = obj.success;
  document.querySelector(id).addEventListener("touchstart",touchStart,false);
  document.querySelector(id).addEventListener("touchmove",touchMove,false);
  document.querySelector(id).addEventListener("touchend",touchEnd,false);
  var $listLoad = document.querySelector(load);
  function touchStart(event){
    var touch = event.touches[0];
    startX = touch.pageX;
    startY = touch.pageY;
  }
  function touchMove(event){
    var touch = event.touches[0];
    endX = touch.pageX;
    endY = touch.pageY;
    $listLoad.style.display="block";
    if((endY - startY)>100){
      $listLoad.innerHTML="下拉刷新"
    }
    if((endY - startY)>200){
      $listLoad.innerHTML="释放立即刷新"
    }
    if(40>(endY - startY)>0){
      $listLoad.style.marginTop = (endY-startY)+"px"
    }
  }
  function touchEnd(event){
    if(endY-startY>10){
      $listLoad.innerHTML="正在加载"
      success();
    }
  }

}

/*
  检测是否含有classname
  有则返回false
  没有返回true
  这是来返回 按钮是否能被点击
*/
function tell(obj,classNames) {
    var classNames = $(obj).hasClass(classNames);
    return !classNames;
}

/*
* 获取url参数
*/
function GetRequest() {

    var url = location.search;//获取url中"?"符后的字符串
    var theRequest = new Object();
    if (url.indexOf("?")!=-1){  //存在？ 则
        var str = url.substr(1);
        strs = str.split("&");  //字符串分割
        for(var i=0;i<strs.length;i++){
            theRequest[strs[i].split("=")[0]]=(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}

/*
  tab选项卡
*/

function ChangeDiv(divId,divName,zDivCount)
{
    for(i=0;i<zDivCount;i++)
    {
        document.getElementById(divName+i).style.display="none";
    }
    document.getElementById(divName+divId).style.display="";
}

/*
* 自动消失的tips
*/
// var tipsarr=[]
function tips(str,type){
  var text = str;
  var marginT = 0;
  // if(type==0){
  //   tipsarr.push(str);
  // }
  //   console.log('移除前：'+ tipsarr);
  // if(tipsarr.length>1){
  //   setTimeout("tips('0',1)",2000)
  // }
  var html = '<div class="tips" style="display:none;margin-top:'+marginT+'px">'+
                  text
              '</div>'
  $("body").append(html);
  $('.tips').fadeIn();
  setTimeout("$('.tips').fadeOut('slow',function(){$('.tips').remove()})",1000);
}
// function reTips(){
//   console.log(tipsarr);
//   $('.tips').remove();
//   tipsarr.shift();
//   console.log('移除后:'+tipsarr);
// }

/*
  提示信息
*/
function toast(str) {
    var text=str;
    var html = "	<div id='toast'> "+
        "<div class='weui_mask_transparent'></div>"+
        "<div class='weui_toast'>"+
        "<i class='weui_icon_toast'>√</i>"+
        "<p class='weui_toast_content'>"+text+"</p>"+
        "</div>"+
        "</div>"
    $("body").append(html);
    setTimeout(" $('#toast').remove();",1000)
}
function dialog1(obj) {
    if(obj){
        var title = obj.title;
        var str = obj.content;
        this.removeDialog = obj.canel||function () {
                $("#dialog2").remove()
            }
        var html = "<div class='weui_dialog_alert' id='dialog2'>"+
            "<div class='weui_mask'></div>"+
            "<div class='weui_dialog'>"+
            "<div class='weui_dialog_hd'><strong class='weui_dialog_title'>"+title+"</strong></div>"+
            "<div class='weui_dialog_bd'>"+str+"</div>"+
            "<div class='weui_dialog_ft'>"+
            "<a href='javascript:;' class='weui_btn_dialog primary' onclick='removeDialog()'>确定</a>"+
            "</div>"+
            "</div>"+
            "</div>"
        $("body").append(html);
    }
}
function dialog(obj) {
    var title = obj.title;
    var content = obj.content;
    this.sure = obj.sure;
    this.canel = obj.canel||function(){
      $("#dialog2").remove();
    };
    var html = "<div class='weui_dialog_alert' id='dialog2'>" +
        "<div class='weui_mask'></div>" +
        "<div class='weui_dialog'>" +
        "<div class='weui_dialog_hd'><strong class='weui_dialog_title'>" + title + "</strong></div>" +
        "<div class='weui_dialog_bd'>" + content + "</div>" +
        "<div class='weui_dialog_ft'>" +
        "<a href='javascript:;' class='weui_btn_dialog default' onclick='canel()'>取消</a>" +
        "<a href='javascript:;' class='weui_btn_dialog primary' onclick='sure()'>确定</a>" +
        "</div>" +
        "</div>" +
        "</div>"
    $("body").append(html);
}
window.alert = function(obj) {
    dialog1(obj)
};
window.confirm = function (obj) {
    dialog(obj)
}
