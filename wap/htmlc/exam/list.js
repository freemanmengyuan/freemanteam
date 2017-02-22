/**
 * Created by Administrator on 2016/4/19.
 * 作业列表
 */
(function () {
  new Vue({
    el:'body',
    data:{
      examFns:[],
      exams:[],
      loading:false
    },
    ready:function(){
      var self = this;
      $.ajax({
          url:apiUrl+"myexamwork",//待完成
          type:'get',//提交方式 GET POST PUT DELETE
          data: {'key':key},//传给服务器的值
          dataType:'json',
          success:function(data) {
              var data = data;
              //console.log(data)
              if(data.code=="100"){
                self.exams = data.data
              }else if(data.httpcode == '600'){
                alert({
                  title:'提示',
                  content:'会话超时<br>请返回微信聊天窗口重新进入',
                  canel:function(){
                      $("#dialog2").remove();
                      WeixinJSBridge.call('closeWindow');
                  }
                })
              }else{
                tips(data.msg)
              }
              self.loading = true;
          },
          error:function () {
              self.loading = true;
          }
      });
      $.ajax({
          url:apiUrl+"myexamworkfinish",//已完成
          type:'get',//提交方式 GET POST PUT DELETE
          data: {'key':key},//传给服务器的值
          dataType:'json',
          success:function(data) {
              var data = data;
              if(data.code=="100"){
                  self.examFns = data.data
              }else if(data.httpcode='600'){
                alert({
                  title:'提示',
                  content:'会话超时<br>请返回微信聊天窗口重新进入',
                  canel:function(){
                      $("#dialog2").remove();
                      WeixinJSBridge.call('closeWindow');
                  }
                })
              }else{
                tips(data.msg)
              }
            self.loading = true;
          },
          error:function(){
            self.loading = true;
          }
      });
    },
    methods:{
      jump:function(index,type){
        if(type===1){
          window.location.href = '/wap/htmlv/exam/finish.html?'+this.examFns[index].id
        }else if(type===0){
          window.location.href = '/wap/htmlv/exam/main.html?'+this.exams[index].id
        }
      }
    }
  })
})()
