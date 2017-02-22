/**
 * Created by Administrator on 2016/4/20.
 */
(function () {
    var userid = localStorage.userid;
    var schoolid = localStorage.schoolid;
    var courseid = window.location.search.substr(1);
    $.ajax({
        url:apiUrl+"coursetree",//服务器请求地址
        type:'get',//提交方式 GET POST PUT DELETE
        data: {'userid':userid,'key':key,'schoolid':schoolid,'courseid':courseid},//传给服务器的值
        dataType:'json',
        success:function(data) {
            var data = data;
           // console.log(data)
            if(data.code =='100'){
                var logo;
                if(data.logo){
                    logo =localStorage.wip+data.logo;
                }else {
                    logo = courseDefault;
                }
                $(".sz_ziyuan").append("<img src='"+logo+"'>")
                var getTpl = document.getElementById('courseMain').innerHTML;//根据模板ID获取<script>模板
                laytpl(getTpl).render(data, function(html){//渲染处理
                    $('.ui-btn-active').show();
                    document.getElementById('Div_0').innerHTML = (html);//渲染到视图容器
                });
            }else if(data.httpcode=="600"){
                //alert(data.message)
                alert({
                  title:'提示',
                  content:'会话超时<br>请返回微信聊天窗口重新进入',
                  canel:function(){
                      $("#dialog2").remove();
                      WeixinJSBridge.call('closeWindow');
                  }
                })
                return false;
            }else{
              tips(data.msg)
            }
        }
    });
})()

/*
*  为空 ""  对应的是 7.png
*  'jpg','gif','png','jpeg','bmp'  对应2.png
*  case "ppt":
*  case "pptx": $msg["name"] = "pptx演示文稿"; $msg["icon"] = "8.png"; break;
*  case "pdf": $msg["name"] = "pdf演示文稿"; $msg["icon"] = "1.png"; break;

* case "swf": $msg["name"] = "动画类"; $msg["icon"] = "5.png"; break;
* case "doc":
* case "docx": $msg["name"] = "word文档"; $msg["icon"] = "1.png"; break;
* case "xls":
* case "xlsx": $msg["name"] = "Excel表格"; $msg["icon"] = "12.png"; break;
* */
