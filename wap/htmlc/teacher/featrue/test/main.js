
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
      answer:"",
      doType:false,
      loading:false,
      activeT:[],
      retrue:true,
      btnmsg:'提交',
      showstudent:false,
      answerlist:[]
    },
    ready:function(){
      var self = this;
      $.ajax({
        url:apiUrl+'sign/classTest/testInfo',
        type:"POST",
        data:{"key":key,"questionid":self.testid},
        dataType:"json",
        success:function(data){
          self.loading = true;
          if(data.httpcode=="200"){
            self.testmain = data.result[0]

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
      testsub:function(){
        this.showstudent = true;
        var self = this;
        $.ajax({
          url:apiUrl+'sign/classTest/testAnsInfo',
          type:"POST",
          data:{"key":key,"questionid":self.testid},
          dataType:"json",
          success:function(data){
            if(data.httpcode=="200"){
                self.answerlist = data.result;

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
        }
      }
  })
})()
