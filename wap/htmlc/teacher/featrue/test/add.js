var vm = new Vue({
  el:'body',
  data:{
    answer:"",
    answercheck:[],
    option:[],
    type:1,
    testmain:{
      type:1,
      stem:'',
      options:["1","2","3","4"]
    },
    msg:'',
    btnmsg:'提交'
  },
  ready:function(){

  },
  methods:{
    testsub:function(){
      var self = this;
      var type = this.type;
      var $input = $("input[type=text]");
      var data={
        stem:'',
        options:[],
        answer:'',
        key:key,
        type:type
      };
      if(type==0){
        data.options = ["对","错"];
        data.stem = $('.sz_bord input').val();
      }else{
        $.each($input,function(index,obj){
          if(obj.value==""){
            return false;
          }
          if(index!==0){
            data.options.push(obj.value);
          }else{
            data.stem=obj.value;
          }
        });
      }
      if(type!=2){
        data.answer = this.answer;
      }else{
        var arr = this.answercheck.sort();
        data.answer = arr.join("");
      }
      if(data.options.length==0 && data.stem==""){
        tips("选项标题不能为空");
        return false;
      }else if(!data.answer){
        tips("请选择答案");
        return false;
      };
      self.btnmsg = "提交中";
      $.ajax({
        url:apiUrl+"sign/classTest/createTest",
        type:"POST",
        data:data,
        dataType:"json",
        success:function(data){
          //请求成功
          self.loading = true;
          if(data.httpcode=='200'){
            tips("创建成功");
            //history.go(-1);
          }else{
            alert({
              title:"提示",
              content:data.message,
              canel:function(){
                $("#dialog2").remove();
              }
            })
          }
        },
        error:function(){
          //执行失败
        },
        complete:function(){
          self.btnmsg="提交"
        }
      })
    },
    addtest:function(){

    },
    optionadd:function(){
      console.log(this.testmain.options)
      this.testmain.options.push(this.testmain.options.length.toString());
    },
    optioncut:function(){
      this.testmain.options.pop();
    }
  },
  watch:{
    'type':function(){
      this.answer=0;
      this.answercheck = [];
    }
  }
})
