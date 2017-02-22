new Vue({
  el:'body',
  data:{
    createtime:'',
    stdlisted:[]
  },
  ready:function(){
    var self = this;
    $.ajax({
      url:apiUrl+'sign/teacher_new_course/index',
      type:"POST",
      dataType:'json',
      data:{'key':key},
      success:function(data){
        var create_time = data.result.created_time;
        self.createtime = create_time.split(" ")[0];
        $('#code').qrcode("http://210.12.84.147/index.php/sign/student_sign/index?classid="+data.result.classid+"&schoolid="+data.result.schoolid);
      },
      error:function(err) {
      }
    });
    this.stdlist();
  },
  methods:{
    stdlist:function(){
      var self = this;
      $.ajax({
        url:apiUrl+'sign/studentsigninfo',
        type:"POST",
        dataType:'json',
        data:{'key':key},
        success:function(data){
          self.stdlisted = data.result;
          setTimeout(self.stdlist,15000)
        },
        error:function(err){

        }
      })
    }
  },
  computed:{
    stdlists:function(){
      var arr = this.stdlisted;
      for(var i=0;i<arr.length;i++){
        if(arr[i]===null){
          arr[i]={truename:"佚名"}
        }
      }
      return arr;
    }
  }
})
