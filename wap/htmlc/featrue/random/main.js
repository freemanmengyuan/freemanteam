/*
* 2016.5.26 讨论内容
*/


  var options = GetRequest();
  var moreid=1;
  var msgarr=[];
  $('.loadF').click(function(){
      getMsg(1);
  })
  // $('.loadmore').click(function(){
  //   moreid++;
  //   getMsg(0);
  // })
  function getMsg(id){
    if(id===1){
      morid=1
    }
    var questionid = $('.questionid').val();
    if(questionid===""){return false}
    $.ajax({
      url:apiUrl+"sign/stuAskQuestion/getAnswerInfo",
      type:"POST",
      data:{"key":key,"questionid":questionid,"moreid":moreid},
      dataType:"json",
      success:function(data){
        var html = "";
        if(data.httpcode=="200"){
            if(data.result.length===0){

            }else{
              var arrMsg = data.result;

              arrMsg.forEach(function(obj){
                var username = obj.truename || "讲师";
                // if(obj.replyid===data.result.userid){
                  html+="<li class='my'>"+
                            "<p class='msgMain'>"+
                                obj.content+
                            "</p>"+
                            "<span class='discussName'>我</span>"+
                          "</li>"
                // }else{
                //   html+="<li>"+
                //     "<span class='discussName'>"+username+"</span>"+
                //             "<p class='msgMain'>"+
                //                 obj.content+
                //             "</p>"+
                //           "</li>"
                // }
              })
            }
            if(id===1){
              $('.msgList').html(html);
            }else{
                $('.msgList').append(html);
            }
            // moreid = data.result.maxid;
            // console.log(moreid)
            // setTimeout("getMsg()",5000)
        }else{
          parent.alert({
            title:"提示",
            content:data.message
          })
        }

      },
      error:function(){

      }
    });
  }

  /*
  * 发送消息
  */

  (function(){
    $.ajax({
      url:apiUrl+"sign/stuAskQuestion/getAskList",
      type:"POST",
      data:{"key":key},
      dataType:"json",
      success:function(data){
        if(data.httpcode==200){
          $('.loadF').html(data.result.content)
          $('.questionid').val(data.result.id)
          getMsg(1)
        }else{
          parent.alert({
            title:"提示",
            content:data.message,
            canel:function () {
              $("#dialog2",parent.document).remove();
              history.go(-1);
            }
          })
        }
      },
      error:function(){

      }
    })
  })()
$('#sendMsg').click(function(){sendMsg()});
  function sendMsg(){
    var discusstxt = $('textarea[name=discusstxt]').val();
    var questionid = $('.questionid').val();
    var myMsg=""
    if($.trim(discusstxt) !=="" && questionid!==''){
      $('#sendMsg').val('发送中');
      $('#sendMsg').attr('disabled',true);
      $.ajax({
        url:apiUrl+"sign/stuAskQuestion/submitAnswer",
        type:"POST",
        data:{"key":key,"questionid":questionid,"answer":discusstxt},
        dataType:"json",
        success:function( data ){
            if(data.httpcode=="200"){
              getMsg(1)
              $('textarea[name=discusstxt]').val('');
              // myMsg+="<li class='my'>"+
              //           "<p class='msgMain'>"+
              //               discusstxt+
              //           "</p>"+
              //           "<span class='discussName'>本人</span>"+
              //         "</li>";
              // $('.msgList').append(myMsg);
              // msgarr.push(data.result.insert_id);
              // console.log(msgarr)
            }
            $('#sendMsg').val('发送');
            $('#sendMsg').attr('disabled',false);
        },
        error:function(){
          $('#sendMsg').val('发送');
          $('#sendMsg').attr('disabled',false);
        }
      })
    }else{
      parent.alert({
        title:'提示',
        content:'内容为空，或者为加载完成'
      })
    }
  }
