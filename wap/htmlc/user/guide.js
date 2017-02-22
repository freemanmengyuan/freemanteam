/**
 * Created by Administrator on 2016/4/23.
 */
// $.ajax({
//     url:apiUrl+"judgelogin/index",//服务器请求地址
//     type:'get',//提交方式 GET POST PUT DELETE
//     dataType:'json',
//     success:function (data) {
//         var data =data;
//         console.log(data)
//         setTimeout(function () {
//             if(localStorage.schoolid&&localStorage.userid){
//                 alert("1111")
//                 location.href="/wap/htmlv/course/myCourse.html"
//             }else {
//                 alert("1111")
//                 location.href="/wap/htmlv/school/list.html"
//             }
//             // if(data.result.status=="no"){
//             //   location.href="/wap/htmlv/school/list.html"
//             // }else if(data.result.status=="yes"){
//             //     location.href="/wap/htmlv/course/myCourse.html"
//             // }
//         },2000)
//     }
// })
function sj () {
        //alert("未登录")
        location.href="/wap/htmlv/school/list.html"
   
}