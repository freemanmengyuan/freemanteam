/**
 * Created by Administrator on 2016/4/19.
 */
/* 个人资料*/
(function () {
  var vm = new Vue({
    el:'body',
    data:{
      loading:false,
      personMsg:Object
    },
    ready:function(){
      var self = this;
      $.ajax({
          url:apiUrl+"personal",//服务器请求地址
          type:'get',//提交方式 GET POST PUT DELETE
          data: {'key':key},//传给服务器的值
          dataType:'json',
          success:function(data) {
              var data = data;
              //console.log(data)
              if(data.httpcode=="200"){
                  //alert(data.message)
                  self.personMsg = data.result;

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
                tips(data.message)
              }
              self.loading = true;
          }
      });
    },
    computed:{
      indivImg:function(){
        var indivImg = this.personMsg.facepic || peosonDefault;
        return indivImg
      },
      birthday:function(){
        var birthday = this.personMsg.birthday || "无";
        return birthday
      },
      role:function(){
        var role="";
        var roleid = Number(this.personMsg.roleid);
        switch (roleid) {
          case 1:
              role="系统管理员"
              break;
          case 2:
              role="校长"
              break;
          case 3:
              role="教师"
              break;
          case 4:
              role = "学生"
              break;
          case 5:
              role = "企业"
              break;
          case 6:
              role ="社会学习者"
              break;
          default:
              role = "无";
        }
        return role;
      }
    },
    methods:{
      centerExit:function(){
        confirm({
          title:"提示",
          content:"确定要解除绑定，并退出微信？",
          sure:function(){
            $('#dialog2').remove();
            $.ajax({
                url:apiUrl+'exitLogin',
                type:'get',
                data:{"key":key},
                dataType:'json',
                success:function (data) {
                    if(data.httpcode=="200"){
                        localStorage.removeItem("userid");
                        localStorage.removeItem("schoolid");
                        toast("解除成功");
                        setInterval('parent.window.location.href="/wap/htmlv/school/list.html"',1000)
                    }else{
                        alert({
                            title:"提示",
                            contetn:data.message
                        })
                    }
                },
                error:function () {
                    //
                    console.log(data)
                }
            })
          }
        })
      }
    }
  })

})()
