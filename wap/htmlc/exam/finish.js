/**
 * Created by Administrator on 2016/4/21.
 */
var testdata;
(function () {
    var userid = localStorage.userid;
    var schoolid = localStorage.schoolid;
    var examid = window.location.search.substr(1);
    $.ajax({
        url:apiUrl+"myexamcontent",//服务器请求地址
        type:'get',//提交方式 GET POST PUT DELETE
        data: {'userid':userid,'key':key,'schoolid':schoolid,"examid":examid},//传给服务器的值
        dataType:'json',
        success:function(data) {
            var data = data;
            if(data.code == '100'){
                var getTpl = document.getElementById('workFinish').innerHTML
                // console.log(data)
                laytpl(getTpl).render(data, function(html){//渲染处理
                    $(".ui-content").html(html)
                });
            }else if(data.httpcode == '600'){
              alert({
                title:'提示',
                content:'会话超时<br>请返回微信聊天窗口重新进入',
                canel:function(){
                    $("#dialog2").remove();
                    WeixinJSBridge.call('closeWindow');
                }
              })
            }else{
              tips(data.msg)
            }
        }
    });
})()
