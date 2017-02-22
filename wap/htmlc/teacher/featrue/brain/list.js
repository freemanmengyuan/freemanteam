/*
* 2016.5.26 讨论列表
*/

(function(){
    $.ajax({
      url:apiUrl+"sign/brainstorm/getBsIist",
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
              "window.location.href='/wap/htmlv/featrue/brain/main.html?topicid="+
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

    /*
    * 下拉刷新
    */
    reLoad({
      main:".listNormal",
      load:".listLoad",
      success:function(){
        //$('.listLoad').css("margin-top","0");
        $('.listLoad').html('正在加载');

        $.ajax({
          url:apiUrl+"sign/brainstorm/getBsIist",
          type:"POST",
          data:{"key":key},
          dataType:"json",
          success:function(data){
            if(data.httpcode=='200'){
                $('.listLoad').html('加载完毕');
                $('.listLoad').slideUp(1000,function() {
                  $('.listLoad').css("margin-top","0")
                });
              var html = '';
              var allArr = data.result;
              if(allArr){
                allArr.forEach(function(obj){
                  html+="<li onclick="+
                  "window.location.href='/wap/htmlv/featrue/brain/main.html?topicid="+
                  obj.id+"&content="+obj.content+"'>"+obj.content+"</li>"
                })
              }else{
                html+="<div class='listLoad'>暂无讨论信息</div>"
              }
              $('.disscussList').html(html);
          }
        }
      })
      }
    })

    $('.js_sub').on('click',function(){
      //prompt层
      layer.prompt({
        title: '请输入头脑风暴标题',
        formType: 0 //prompt风格，支持0-2
      }, function(pass){//pass为文本内容
        if($.trim(pass)==""){
          tips("内容不能为空")
        }else{
          $.ajax({
            url:apiUrl+'sign/brainstorm/createBsTopic',
            data:{'key':key,'topic':pass},
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
