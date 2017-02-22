/**
 * Created by Administrator on 2016/4/15.
 */
(function () {
  var vm = new Vue({
    el:"body",
    data:{
      curseLists:[],
      loading:false
    },
    ready:function(){
      var self = this;
      $.ajax({
          url:apiUrl+"course",//服务器请求地址
          type:'get',//提交方式 GET POST PUT DELETE
          data: {'key':key},//传给服务器的值
          dataType:'json',
          success:function(data) {
              var data = data;
              self.loading = true;
              //console.log(data)
                  if(data.code=="100"){
                    self.curseLists=data.data;
                  }else if(data.code=="300"){
                      self.curseLists=[];
                  }
                    else{
                      alert(data.message)
                      return false;
                    }
          },
          error:function () {
              // alert({
              //     "title":"通知",
              //     "content":"网络异常,请尝试刷新页面"
              // })
          }
      });
    },
    methods:{
      jumpcour:function(index){
        window.location.href='/wap/htmlv/course/maintest.html?'+this.curseLists[index].courseid
      }
    }
  })
})()
