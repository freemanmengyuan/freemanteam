/*
* 对教师的评价
*/
(function(){
  $(".star").on("click","li",function(){
    var starIndex = $(this).index()+1;
    $('input[name=star]').val(starIndex);
    $('.star li').removeClass('active');

    /*
    :lt 选择器选取带有小于指定 index 值的元素。
    index 值从 0 开始。
    */

    $('.star li:lt('+starIndex+')').addClass('active');
  })

  $("#addeva").click(function(){
    var self = this;
    var star = $('input[name=star]').val();
    var content = $('textarea[name=evalcont]').val();
    if(star==""){
      parent.alert({
        title:"提示",
        content:"请评星"
      })
    }else if($.trim(content)==""){
      parent.alert({
        title:"提示",
        content:"请输入课程评价"
      })
    }else{
      $(self).attr('disabled',true);
      $(self).val('提交中')
        $.ajax({
          url:apiUrl+"sign/evaluate/addEvaContent",
          type:"POST",
          data:{"key":key,"star":star,"content":content},
          dataType:"json",
          success:function(data){
            if(data.httpcode=="200"){
              parent.toast(data.message);
              setInterval("history.go(-1)",1000)
            }else{
              parent.alert({
                title:"提示",
                content:data.message
              })
            }
            $(self).attr('disabled',false);
            $(self).val('提交')
          },
          error:function(){
            $(self).attr('disabled',false);
            $(self).val('提交')
            //错误信息
          }
        })

      }
    })
})()
