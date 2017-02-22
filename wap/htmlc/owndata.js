/**
 * Created by Administrator on 2016/4/15.
 * 左侧导航的 个人信息接口
 */
(function () {
    $.ajax({
        url:apiUrl+"personal",//服务器请求地址
        type:'get',//提交方式 GET POST PUT DELETE
        data: {'key':key},//传给服务器的值
        dataType:'json',
        success:function(data) {
            var data = data;
          //console.log(data)
            if(data.httpcode == '200'){
              localStorage.wip=data.result.wip.wip;
              $('.ui-title').html(data.result.schoolname)
                var getTpl = document.getElementById('ownModel').innerHTML;//根据模板ID获取<script>模板
                //console.log(data)
                laytpl(getTpl).render(data, function(html){//渲染处理
                    $("#owndata").html(html);//渲染到视图容器
                    var role;
                    var roleid=Number(data.result.roleid)
                    switch(roleid){
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
                            //alert("error")
                    }
                    $('.sz_zhich').html(role)
                    var imgDefault = data.result.facepic || peosonDefault;
                    $('.sz_tou img').attr("src",imgDefault);
                    document.title = data.result.schoolname;
                });
            }else if(data.httpcode=='600'){
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
              return false;
              }
        }
    });
    /*
    * 解除绑定接口
    */
    $('.unbind').click(function(){
      confirm({
        title:"提示",
        content:"确定要解除绑定，并退出微信？",
        sure:function(){
          $("#dialog2").remove();
          $.ajax({
              url:apiUrl+'exitLogin',
              type:'get',
              data:{"key":key},
              dataType:'json',
              success:function (data) {
                  if(data.httpcode=="200"){
                      localStorage.removeItem("userid");
                      localStorage.removeItem("schoolid");
                      toast(data.message);
                      setInterval('parent.window.location.href="/wap/htmlv/school/list.html"',2000)

                  }else{
                      alert({
                          title:"提示",
                          contetn:data.message
                      })
                  }
              },
              error:function () {
                  //
                  //console.log(data)
              }
          })
        }
      })
})

    /*
    *遮罩层动画
    */
    // $('body').on('click','#zhezhao',function(){
    //   $('.zhezhao').fadeIn(300);
    // })
})()
