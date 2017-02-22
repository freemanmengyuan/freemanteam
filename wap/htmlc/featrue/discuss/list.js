/*
* 2016.5.26 讨论列表
*/

(function(){
    $.ajax({
      url:apiUrl+"sign/studentDiscussion/getDisInfo",
      type:"POST",
      data:{"key":key},
      dataType:"json",
      success:function(data){
        //请求成功
        if(data.httpcode=='200'){
          var html = '';
          var allArr = data.result;
          if(allArr){
            allArr.forEach(function(obj){
              html+="<li onclick="+
              "window.location.href='/wap/htmlv/featrue/discuss/main.html?topicid="+
              obj.id+"&content="+encodeURIComponent(obj.content)+"'>"+obj.content+"</li>"
            })
          }else{
            html+="<div class='listLoad'>暂无讨论信息</div>"
          }
          $('.listNormal').append(html);
        }else{
          parent.alert({
            title:"提示",
            content:data.message,
            canel:function(){
              $("#dialog2",parent.document).remove();
              history.go(-1);
            }
          })
        }
      },
      error:function(){
        //执行失败
      }
    })


})()
