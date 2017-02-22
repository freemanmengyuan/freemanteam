/**
 * Created by Administrator on 2016/4/15.
 */

    var schoolname,
        schoolid;
    shoolname = window.localStorage.schoolname;
    schoolid = window.localStorage.schoolid;
    $("#schoolname").val(shoolname);
    $("#schoolname").attr("data-id",schoolid);
function login() {
    var username = $("input[name=username]").val();
    var password = $("input[name=userword]").val();
    if($('.login').hasClass('disabled')){
        return false;
    }
    //console.log(username+"<br>"+password)
    if(username == ""){
        alert({
            title:"",
            content:"用户名不能为空"
        })
        return false;
    }else if(password ==""){
        alert({
            title:"",
            content:"密码不能为空"
        })
        return false;
    }else if(schoolid==""){
        //alert(schoolid)
        alert({
            title:"",
            content:"重新选择学校"
        })
        return false;
    }
    $('.login').html('登陆中');
    $('.login').addClass('disabled');
    $.ajax({
        url: apiUrl + "user",
        type:'post',
        data: {'key':key,'username':username,'password':password,'schoolid':schoolid,'openid':Math.round(Math.random() * 1000)},//传给服务器的值
        dataType:'json',
        success:function (data) {
            //onsole.log(JSON.stringify(data))
            $('.login').html('马上登录');
            $('.login').removeClass('disabled');
            if(data.httpcode =="208"){
               // console.log(data)
               //  alert({
               //      title:"",
               //      content:data.message
               //  })
                    localStorage.setItem("userid",data.result.userid);
                    localStorage.setItem("schoolid",data.result.schoolid);
                    localStorage.setItem("wip",data.result.wip);
                    window.location.href=AllCourse;
            }
            else if(data.httpcode =="200"){
                //console.log(data)
                window.location.href=AllCourse;
            }else{
                alert({
                    title:"",
                    content:data.message
                });
            }
        },
        error:function (textStatus, errorThrown) {
            //错误回掉函数
        }
    })
}
