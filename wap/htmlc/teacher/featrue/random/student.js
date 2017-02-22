/*
* 2016.5.26 讨论列表
*/

(function(){
  var urloption = GetRequest();
    $.ajax({
      url:apiUrl+"sign/askQuestion/randomAsk",
      type:"POST",
      data:{"key":key,'questionid':urloption.questionid},
      dataType:"json",
      success:function(data){
        //请求成功
        if(data.httpcode=='200'){
          var html = '';
          var allArr = data.result;
          if(allArr){
            allArr.forEach(function(obj){
              html+="<li onclick="+
              "window.location.href='/wap/htmlv/teacher/featrue/random/student.html?questionid="+
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
            }
          })
        }
      },
      error:function(){
        //执行失败
      }
    })
    $('.js_sub').on('click',function(){
      prompt({title:'请输入提问内容',sure:function(){
        var datacont = $('.weui_dialog_prompt').val();
        if($.trim(datacont)==""){
          tips("内容不能为空")
        }else{
          $.ajax({
            url:apiUrl+'sign/askQuestion/createQuestion',
            data:{'key':key,'question':datacont},
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
      }})
    })

})()
