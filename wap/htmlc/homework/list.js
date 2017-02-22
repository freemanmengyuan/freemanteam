/**
 * Created by Administrator on 2016/4/19.
 */
 (function () {
   new Vue({
     el:'body',
     data:{
       homeFns:[],
       homes:[],
       loading:false
     },
     created:function(){
       var self = this;
       $.ajax({
           url:apiUrl+"myhomework",//待完成
           type:'get',//提交方式 GET POST PUT DELETE
           data: {'key':key},//传给服务器的值
           dataType:'json',
           success:function(data) {
               var data = data;
               //console.log(data)
               if(data.code == '100'){
                 self.homes = data.data
               }else if(data.httpcode =='600'){
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
           url:apiUrl+"myhomeworkfinish",//已完成
           type:'get',//提交方式 GET POST PUT DELETE
           data: {'key':key},//传给服务器的值
           dataType:'json',
           success:function(data) {
               var data = data;
               if(data.code=="100"){
                   self.homeFns = data.data
               }else if(data.httpcode=='600'){
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
           },
           error:function(){
           }
       });
     },
     methods:{
       jump:function(index,type){
         if(type===1){
           window.location.href = '/wap/htmlv/homework/finish.html?'+this.homeFns[index].id
         }else if(type===0){
           window.location.href = '/wap/htmlv/homework/main.html?'+this.homes[index].id
         }
       }
     }
   })
 })()
