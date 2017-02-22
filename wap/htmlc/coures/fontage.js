/**
 * Created by Administrator on 2016/4/20.
 */
(function () {
    var userid = localStorage.userid;
    var schoolid = localStorage.schoolid;
    var masterid = window.location.search.substr(1);
    $.ajax({
        url:apiUrl+"resources",//服务器请求地址
        type:'get',//提交方式 GET POST PUT DELETE
        data: {'userid':userid,'key':key,'schoolid':schoolid,'masterid':masterid},//传给服务器的值
        dataType:'json',
        success:function(data) {
            var data = data;
            if(data.code=='100'){
                var type = data.data.convert2ext;
                var html="";
                var src = "";
                var logo = '';
                /*
                * 判断是否是云资源 如果是云资源 则不加外网地址
                * */
                if(data.data.logo){
                    if(data.data.logo.indexOf("http://")!=-1){
                        logo = data.data.logo;
                    }else{
                        logo=localStorage.wip+data.data.logo;
                    }
                }
                if(data.data.filepath){
                    if(data.data.filepath.indexOf("http://")!=-1){
                        src = data.data.filepath;
                    }else{
                        src=localStorage.wip+data.data.filepath;
                    }
                }

                /*
                * 判断素材类型 不同类型渲染不同标签
                * */
                switch(type) {
                    case "mp4":
                        html = "<video src='" + src + "' width='100%' height='400' controls='' poster='"+logo+"'>"
                            + " 您的浏览器不支持 video 标签。</video>"
                        break;
                    case "mp3":
                        html ="<div class='mp3Gif'><img src='/wap/images/mp3.gif' width='100%'></div>"+
                            "<audio src='" + src + "' width='100%' height='400' controls=''>"
                            + " 您的浏览器不支持 audeo 标签。</audio>"
                        break;
                    default:
                        data.data.totalpage = (data.data.totalpage === "0") ? 1 : data.data.totalpage;
                        html += "<ul class='bxslider'><li><img src='" + src+ "'/></li>"
                        for (var i = 1; i < data.data.totalpage; i++) {
                            html += "<li><img src='" + fontageDafult + "'></li>"
                        }
                        html += "</ul>" +
                            "<div class='rangeBox'>" +
                            "<span class='rangMin'>1</span><span class='rangMax'>" + data.data.totalpage + "</span>" +
                            "<input type='range' name='points' min='1' max='" + data.data.totalpage + "' style='width: 100%'  value='1'/></div>" +
                            "<div class='tzImg'> 查看第 <input type='text' name='imgNum' value='1'> 张图片 </div>"
                }
                $('.priviewNav').html(html);
                $("body").append("<script src='../../js/tree.js'></script>")
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
