/**
 * Created by Administrator on 2016/4/19.
 */
 (function () {
   new Vue({
     el:'body',
     data:{
       homePulnos:[],//未发布的作业
       homeFns:[],//已批改
       homes:[],//未批改
       loading:false
     },
     created:function() {
       var self = this;
       $.ajax({
           url:apiUrl+"/sign/zjhometeacher/homelist",//未批改
           type:'get',//提交方式 GET POST PUT DELETE
           data: {'key':key,'ispublish':1},//传给服务器的值
           dataType:'json',
           success:function(data) {
               var data = data;
               //console.log(data)
               if(data.code == '200'){
                 self.homes = data.data
               }else if(data.httpcode =='600'){
                 alert({
                   title:'提示',
                   content:'会话超时<br>请返回微信聊天窗口重新进入',
                   canel:function() {
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
    	   url:apiUrl+"/sign/zjhometeacher/homelist",//已批改
           type:'get',//提交方式 GET POST PUT DELETE
           data: {'key':key,'ispublish':2},//传给服务器的值
           dataType:'json',
           success:function(data) {
               var data = data;
               if(data.code=="200"){
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
       $.ajax({
    	   url:apiUrl+"/sign/zjhometeacher/homelist",//未发布
           type:'get',//提交方式 GET POST PUT DELETE
           data: {'key':key,'ispublish':3},//传给服务器的值
           dataType:'json',
           success:function(data) {
               var data = data;
               if(data.code=="200"){
                   self.homePulnos = data.data
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
    	   if(this.homes[index].workcount=="0"||this.homes[index].workcount==" "){
    		   tips("该作业下没有题！");
    		   return false;
    	   }
    	   if(type=="1"){
    		   window.location.href = '/wap/htmlv/teacher/home/studentlist.html?'+this.homes[index].id   
    	   }
    	   if(type=="2"){
    		   window.location.href = '/wap/htmlv/teacher/home/studentlist.html?'+this.homeFns[index].id   
    	   }
    	   if(type=="3"){
    		   window.location.href = '/wap/htmlv/teacher/home/studentlist.html?'+this.homePulnos[index].id   
    	   }              
       }
     }
   })
 })()
