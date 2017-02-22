/*
* 2016.5.26 讨论列表
*/

(function(){
    $.ajax({
      url:apiUrl+"sign/askQuestion/getAskList",
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
              "window.location.href='/wap/htmlv/teacher/featrue/random/main.html?questionid="+
              obj.id+"&content="+encodeURIComponent(obj.content)+"'>"+obj.content+"</li>"
            })
          }else{
            html+="<div class='listLoad'>暂无讨论信息</div>"
          }
          $('.disscussList').append(html);
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
    $('.js_sub').on('click',function(){
      //prompt层
      layer.prompt({
        title: '请输入提问内容',
        formType: 0 //prompt风格，支持0-2
      }, function(pass){//pass为文本内容
        if($.trim(pass)==""){
          tips("内容不能为空")
        }else{
          $.ajax({
            url:apiUrl+'sign/askQuestion/createQuestion',
            data:{'key':key,'question':pass},
            dataType:'json',
            type:'POST',
            success:function(data){
              if(data.httpcode=="200"){
                $('#dialog2').remove();
                tips('创建成功');
                window.location.reload();
              }
            }
          })
        }
      });
    })

})()
