/**
 * Created by Administrator on 2016/4/19.
 */
 (function () {
   new Vue({
     el:'body',
     data:{
       studentPulnos:[],//未提交
       studentFns:[],//未批改
       students:[],//已批改
       loading:false
     },
     created:function(){
       var self = this;
       var examid = window.location.search.substr(1);
       $.ajax({
           url:apiUrl+"/sign/zjexamteacher/studentlist",//已批改
           type:'get',//提交方式 GET POST PUT DELETE
           data: {'key':key,'isdone':3,'examid':examid},//传给服务器的值
           dataType:'json',
           success:function(data) {
               var data = data;
               //console.log(data)
               if(data.code == '200'){
                 self.students = data.data
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
    	   url:apiUrl+"/sign/zjexamteacher/studentlist",//未批改
           type:'get',//提交方式 GET POST PUT DELETE
           data: {'key':key,'isdone':2,'examid':examid},//传给服务器的值
           dataType:'json',
           success:function(data) {
               var data = data;
               if(data.code=="200"){
                   self.studentFns = data.data
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
    	   url:apiUrl+"/sign/zjexamteacher/studentlist",//未提交
           type:'get',//提交方式 GET POST PUT DELETE
           data: {'key':key,'isdone':4,'examid':examid},//传给服务器的值
           dataType:'json',
           success:function(data) {
               var data = data;
               if(data.code=="200"){
                   self.studentPulnos = data.data
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
    	   if(type=="1"){
    		   window.location.href = '/wap/htmlv/teacher/exam/studentdetail.html?'+this.students[index].myexamid;  
    	   }
    	   if(type=="2"){
    		   window.location.href = '/wap/htmlv/teacher/exam/studentdetail.html?'+this.studentFns[index].myexamid;  
    	   }
    	     	                 
       }
     }
   })
 })()
