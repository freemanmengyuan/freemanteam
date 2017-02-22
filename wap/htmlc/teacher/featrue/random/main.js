var urloption = GetRequest();

$('.listnoe').html(decodeURIComponent(urloption.content));

  $('.js_sub').click(function(){
    $.ajax({
      url:apiUrl+"sign/askQuestion/randomAsk",
      type:"POST",
      data:{"key":key,'questionid':urloption.questionid},
      dataType:"json",
      success:function(data){
        //请求成功
        if(data.httpcode=='200'){
          var html = '';
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
  })
  function getAnswerInfo(){
    $.ajax({
      url:apiUrl+"sign/askQuestion/getAnswerInfo",
      type:"POST",
      data:{"key":key,'questionid':urloption.questionid},
      dataType:"json",
      success:function(data){
        //请求成功
        if(data.httpcode=='200'){
          var html = '<table><tr><td>内容</td><td>时间</td></tr>';
          var pername = data.result[0].truename || "佚名";
          data.result.forEach(function(obj){
            html+="<tr><td>"+obj.content+"</td><td>"+obj.addtime+"</td></tr>"
          })
          html+="</table>";
          $('.name').html(pername);
          $('.js_msg').html(html);
        }else{
          tips(data.message)
        }
      },
      error:function(){
        //执行失败
      }
    });
  }getAnswerInfo();
$('.loadF').click(function(){
  getAnswerInfo();
})
