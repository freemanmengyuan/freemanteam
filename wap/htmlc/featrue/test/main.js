
/*
* 2016.6.2  课堂测验
*/

(function(){
  var options = GetRequest();
  new Vue({
    el:"body",
    data:{
      testid:options.id,
      testmain:Object,
      answer:[],
      doType:false,
      loading:false,
      activeT:[],
      retrue:true,
      btnmsg:'提交'
    },
    ready:function(){
      this.doType = (options.type=='0')?false:true;
      var self = this;
      var testurl = (options.type=="0")?'forTestList':'testFinList';
      $.ajax({
        url:apiUrl+'sign/studentTest/testInfo',
        type:"POST",
        data:{"key":key,"questionid":self.testid},
        dataType:"json",
        success:function(data){
          self.loading = true;
          if(data.httpcode=="200"){
              var testarr = data.result;
              var arr2=[];
              if(self.doType===true){
                for(var i=0;i<testarr[0].options.length;i++){
                  arr2[i]=false;
                }
                var str = testarr[0].answer_info.studentAnswer;
                if(str.length>1){
                  var arr1 = str.split("");
                  arr1.forEach(function(key,value){
                    var k=key.charCodeAt()-65;
                     arr2[k]=true;

                  })
                }else if(str.length===1){
                  var k =str.charCodeAt()-65;
                   arr2[k]=true;
                }
                self.activeT=arr2;
              }
              // testarr.forEach(function(key){
              //   if(key.id == options.id){
              //     self.testmain = key;
              //   }
              self.testmain = testarr[0];

          }else{
            parent.alert({
              title:"提示",
              content:data.message
            })
          }
        },
        error:function(){

        }
      })
    },
    methods:{
      testsub:function(e){
        var self = this;
        if(!self.retrue){
          return false
        }
        var myanswe;
        var testid = this.testid;
        var answer = this.answer;
        if(answer.length===0){
          alert({
            title:"提示",
            content:"请选择答案"
          });
          return false;
        }else{
          if(this.testmain.type=="2"){
              answer.sort();
              myanswer=answer.join("");
          }else{
              myanswer=answer;
          }
          //console.log(e)
          self.retrue = false;
            $.ajax({
              url:apiUrl+'sign/studentTest/submitAnswer',
              type:"POST",
              data:{"key":key,"questionid":testid,"studentAnswer":myanswer},
              dataType:"json",
              success:function(data){
                if(data.httpcode == "200"){
                  toast(data.message);
                  setTimeout("window.location.href='/wap/htmlv/featrue/test/list.html'",1000)
                }else {
                    self.retrue = true;
                }
              },
              error:function(){
                self.retrue = true;
              }
            })
        }
      }
    }
  })
})()
