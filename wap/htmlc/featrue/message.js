(function () {
    var userid = localStorage.userid;
    var schoolid = localStorage.schoolid;
    $.ajax({
        url:apiUrl+"personal",//服务器请求地址
        type:'get',//提交方式 GET POST PUT DELETE
        data: {'key':key,'schoolid':schoolid,'userid':userid},//传给服务器的值
        dataType:'json',
        success:function(data) {
            var data = data;
            //console.log(data)
            if(data.httpcode!="200"){
                //alert(data.message)
                alert({title:"提示",content:data.message})
                return false;
            }
            else{
              var facepic = data.result.facepic||peosonDefault;
                    $('.myMsg img').attr("src",facepic)
                    $('.nameSex').html(data.result.truename);
              var sex = data.result.sex;
              if(sex=="男"){
                $('.myMsg img').addClass('son');
              }else if(sex=="女"){
                $('.myMsg img').addClass('girl');
              }
            }
        }
    });
})()
