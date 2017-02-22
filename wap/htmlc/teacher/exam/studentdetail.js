/**
 * Created by Administrator on 2016/4/21.
 */
(function () {
    var myexamid = window.location.search.substr(1);
    $.ajax({
        url:apiUrl+"/sign/zjexamteacher/studentdetail",//服务器请求地址
        type:'get',//提交方式 GET POST PUT DELETE
        data: {'key':key,"myexamid":myexamid},//传给服务器的值
        dataType:'json',
        success:function(data) {
            var data = data;
             //console.log(data)
            if(data.code =='200'){            	
                var getTpl = document.getElementById('workFinish').innerHTML
                // console.log(data)
                laytpl(getTpl).render(data, function(html){//渲染处理
                    $(".ui-content").html(html)
                });
                $('#remark').val(data.remark);                
            }else if(data.httpcode == '600'){
              alert({
                title:'提示',
                content:'会话超时<br>请返回微信聊天窗口重新进入',
                canel:function(){
                    $("#dialog2").remove();
                    WeixinJSBridge.call('closeWindow');
                }
              });
            }else{
              tips(data.msg)
            }            
        }
    });
    $('#submit').click(function(){
        var remark=$('#remark').val();
        remark=$.trim(remark);
        if(remark==""||remark==" "){
        	tips("请填写评语!");
        	return false;
        }
        var submit=this;
        keepclick(submit);
        $('#submit').attr("disabled","disabled");
        $.ajax({
            url:apiUrl+"/sign/zjexamteacher/teacherremark",//服务器请求地址
            type:'get',//提交方式 GET POST PUT DELETE
            data: {'key':key,"myexamid":myexamid,"remark":remark},//传给服务器的值
            dataType:'json',
            success:function(data) {
            	  keepclick(submit);         		
            	  var data = data;
                   //console.log(data)
                  if(data.code =='200'){
                	  tips(data.msg);          
                  }else if(data.httpcode == '600'){
                    alert({
                      title:'提示',
                      content:'会话超时<br>请返回微信聊天窗口重新进入',
                      canel:function(){
                          $("#dialog2").remove();
                          WeixinJSBridge.call('closeWindow');
                      }
                    });
                  }else{
                    tips(data.msg)
                  }
                 
            }
        });
    })
})()
