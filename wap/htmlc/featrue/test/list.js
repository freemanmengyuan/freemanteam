/*
* 2016.5.26 讨论列表
*/

(function(){
  new Vue({
    el:"body",
    data:{
      testFinList:[],
      forTestList:[],
      loading:false
    },
    ready:function(){
      var self = this;
      $.ajax({
        url:apiUrl+"sign/studentTest/testList",
        type:"POST",
        data:{"key":key},
        dataType:"json",
        success:function(data){
          //请求成功
          self.loading = true;
          if(data.httpcode=='200'){
            self.testFinList = data.result.done;
            self.forTestList = data.result.nodone;
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
        if(id===0){   //0 是未完成 1 是已完成
            window.location.href="/wap/htmlv/featrue/test/main.html?type=0&id="+this.forTestList[index].id
        }else if(id===1){
            window.location.href="/wap/htmlv/featrue/test/main.html?type=1&id="+this.testFinList[index].id
        }
      }
    }
  })

})()
