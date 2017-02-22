/*
* 主页课程接口
*
*/

(function () {
    new Vue({
      el:"body",
      data:{
        courses:[],
        loading:false
      },
      ready:function(){
        var self = this;
        $.ajax({
            url:apiUrl+"course",//服务器请求地址
            async:true,
            type:'get',//提交方式 GET POST PUT DELETE
            data: {'key':key},//传给服务器的值
            dataType:'json',
            success:function(data) {
                //console.log(data)
                self.loading = true;
                if(data.code=="100") {
                    self.courses = data.data;
                }else if(data.code=="300"){
                    self.courses = data.data;
                }else if(data.httpcode == '600'){
                    alert({
                      title:'提示',
                      content:'会话超时<br>请返回微信聊天窗口重新进入',
                      canel:function(){
                          $("#dialog2").remove();
                          WeixinJSBridge.call('closeWindow');
                      }
                    })
                }
                  else{
                    tips(data.message)
                    return false;
                  }
            },
            error:function () {
                self.loading = true;
                // alert({
                //     "title":"提示",
                //     "content":"网络异常，请尝试刷新页面"
                // })
            }
        });
      },
      methods:{
        jump:function(index){
          window.location.href = '/wap/htmlv/course/main.html?'+this.courses[index].courseid
        }
      }
    })
})()
