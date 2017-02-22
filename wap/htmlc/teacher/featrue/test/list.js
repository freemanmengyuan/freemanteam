/*
* 2016.5.26 讨论列表
*/

(function(){
  new Vue({
    el:"body",
    data:{
      testFinList:[],
      loading:false
    },
    ready:function(){
      var self = this;
      $.ajax({
        url:apiUrl+"sign/classTest/getTestList",
        type:"POST",
        data:{"key":key},
        dataType:"json",
        success:function(data){
          //请求成功
          self.loading = true;
          if(data.httpcode=='200'){
            self.testFinList = data.result;
          }else{
            alert({
              title:"提示",
              content:data.message,
              canel:function(){
                $("#dialog2").remove();
                history.go(-1);
              }
            })
          }
        },
        error:function(){
          //执行失败
          self.loading = true;
        }
      })
    },
    methods:{
      testMain:function(index,id){
        window.location.href="/wap/htmlv/teacher/featrue/test/main.html?id="+this.testFinList[index].id
      },
      jump:function(){
        window.location.href = "/wap/htmlv/teacher/featrue/test/add.html"
      }
    }
  })

})()
