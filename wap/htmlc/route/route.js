/*
* 路由功能 @modefy mengyuan 2016-12-30
*/

(function(){
  /*
  *路由地址对应
  */
  var menuAddress = {
    userinfo:"/wap/htmlv/penrson/center.html", //学生个人资料
    myhomework:"/wap/htmlv/homework/my.html", //学生的作业
    mycourse:"/wap/htmlv/course/myCourse.html", //学生的课程
    myexam:"/wap/htmlv/exam/my.html", //学生的考试
    myindex:"/wap/htmlv/home.html",
    mykthd:"/wap/htmlv/featrue/list.html",
    mysign:"/wap/htmlv/featrue/signstate.html",  //学生互动课堂

    teacherhome:"/wap/htmlv/teacher/home/homelist.html",//教师批改作业
    teachefeatrue:"/wap/htmlv/teacher/featrue/list.html",//教师互动课堂
    teacherexam:"/wap/htmlv/teacher/exam/examlist.html",//教师批改考试
    teacherinfo:"/wap/htmlv/penrson/center.html",//教师个人资料
  }
  /*
  * 获取u要跳转的url
  */
  var addOption = GetRequest();
  /*
  *进行跳转
  */
  if(addOption.fun) {
    var urlMenu = menuAddress[addOption.fun];
    if(addOption.fun =='mysign'){
      var signsrc = urlMenu+'?type='+addOption.type+'&title='+addOption.title+'&msg='+addOption.msg;
      $("iframe").attr("src",signsrc)
    }else if(urlMenu) {
      $("iframe").attr("src",urlMenu)
    }else{
        $("iframe").attr("src",menuAddress.myindex)
      // alert({
      //   title:"提示",
      //   content:"非法链接"
      // })
    }
  }else{
    $("iframe").attr("src",menuAddress.myindex)
  }

})()
