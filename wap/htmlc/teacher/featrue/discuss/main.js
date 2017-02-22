/*
* 2016.5.26 讨论内容
*/


  var options = GetRequest();
  var moreid=1;
  // var msgarr=[];
  $(".loadF").html(decodeURI(options.content))
  $('.loadF').click(function(){
    getMsg(1)
  })
  $('.loadmore').click(function(){
    moreid++;
    getMsg(0);
  })
  function getMsg(id){
    if(id===1){
      morid=1
    }
    $.ajax({
      url:apiUrl+"sign/studentDiscussion/getDisContent",
      type:"POST",
      data:{"key":key,"topicid":options.topicid,"moreid":moreid},
      dataType:"json",
      success:function(data){
        var html = "";
        if(data.httpcode=="200"){
            if(data.result.data.length===0){

            }else{
              var arrMsg = data.result.data;

              arrMsg.forEach(function(obj){
                var username = obj.truename || "讲师";
                if(obj.replyid===data.result.userid){
                  html+="<li class='my'>"+
                            "<p class='msgMain'>"+
                                obj.content+
                            "</p>"+
                            "<span class='discussName'>我</span>"+
                          "</li>"
                }else{
                  html+="<li>"+
                    "<span class='discussName'>"+username+"</span>"+
                            "<p class='msgMain'>"+
                                obj.content+
                            "</p>"+
                          "</li>"
                }
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
  }getMsg();

  /*
  * 发送消息
  */
$('#sendMsg').click(function(){sendMsg()});
  function sendMsg(){
    var discusstxt = $('textarea[name=discusstxt]').val();
    var myMsg=""
    if($.trim(discusstxt) !==""){
      $('#sendMsg').val('发送中');
      $('#sendMsg').attr('disabled',true);
      $.ajax({
        url:apiUrl+"sign/studentDiscussion/addDisContent",
        type:"POST",
        data:{"key":key,"topicid":options.topicid,"content":discusstxt},
        dataType:"json",
        success:function( data ){
            if(data.httpcode=="200"){
                getMsg(1);
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
            $('textarea[name=discusstxt]').val('');
        },
        error:function(){
          $('#sendMsg').val('发送');
          $('#sendMsg').attr('disabled',false);
        }
      })
    }else{
      parent.alert({
        title:"提示",
        content:"讨论信息不能为空"
      })
    }
  }
