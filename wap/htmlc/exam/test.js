/**
 * Created by Administrator on 2016/4/21.
 */
var testdata;;
(function () {
    var userid = localStorage.userid;
    var schoolid = localStorage.schoolid;
    var examid = window.location.search.substr(1);
    $.ajax({
        url:apiUrl+"myexamcontent",//服务器请求地址
        type:'get',//提交方式 GET POST PUT DELETE
        data: {'userid':userid,'key':key,'schoolid':schoolid,'examid':examid},//传给服务器的值
        dataType:'json',
        success:function(data) {
            var data = data;
            if(data.code == '100'){
                $(".sum").html(data.data.length);
                if(data.data.length===1){ //如果长度为1 则直接显示提交
                    $('.next').html('提交');
                }
                testdata=data.data;
                var test = testdata[0];
                var type;
                switch(test.type){
                    case "0":
                        type="单选题"
                        break;
                    case "1":
                        type="多选题"
                        break;
                    case "2":
                        type="判断题"
                        break;
                    default:
                       // alert("题型错误")
                }
                $(".sz_color_a").html("1")
                $(".sz_bord").html(1+".【"+type+"】"+test.content)
                $(".tpoint").html(test.stempoint);
                $("input[name=testNum]").attr("data-id",test.stemid)
                var testmain="";
                if(test.type=="1"){
                    for(var j =0;j<test.options.length;j++){
                        if(test.myanswer){var tj = test.myanswer.indexOf(test.options[j].code) !=-1
                        var checkd=tj?"checked='checked'":""}
                        testmain += "<div class='testoption'><input type='checkbox' class='option-input radio' name='text'"+checkd+" value='"+test.options[j].code+"'><div class='optionmain'>"+test.options[j].code+":"+test.options[j].content+"</div></div>"
                    }
                }else{
                    for(var k =0;k<test.options.length;k++){
                      var testcode;
                      if(test.type==='2'){
                        testcode=''
                      }else if(test.type==='0'){
                        testcode=test.options[k].code+':'
                      }
                        if(test.myanswer){var tj = test.myanswer.indexOf(test.options[k].code)!=-1
                        var checkd=tj?"checked='checked'":""}
                        testmain += "<div class='testoption'><input type='radio' class='option-input radio' name='text'"+checkd+" value='"+test.options[k].code+"'><div class='optionmain'>"+testcode+test.options[k].content+"</div></div>"
                    }
                }
                $(".sz_abcd").html(testmain)
            }else if(data.httpcode = '600'){
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
        }
    });
})()
var arr1=[];
var localarr = []
function textNum(num) {
    var str = "";
    if($("input[name=text]:checked").length<1&&num!="0"){ //判断是否选择
        $('.next').unbind("click")  //防止多次提交
        alert({
            title:"",
            content:"请选择答案",
            canel:function () {
                $('.next').bind('click', function(e) {
                    textNum(1)
                });
                $("#dialog2").remove();
            }
        })
        return false;
    }
    if($(".next").html()=="提交"&&num==1){
        //console.log($(".next").html())
        $('.next').unbind("click")  //防止多次提交
    confirm({
        title:"是否确认提交试卷",
        content:"提交试卷后不能修改答案",
        sure:function () {
            save(2);
            $('.next').bind('click', function(e) {
                textNum(1)
            });
            $("#dialog2").remove();
         },
        canel:function () {
            $("#dialog2").remove();
            $('.next').bind('click', function(e) {
                textNum(1)
            });
        }
    });
    }
    $("input[name=text]:checked").each(function () {
        if(str){
            str=str+","+$(this).val();
        }else{
            str+=$(this).val();
        }

    })
    var id=$("input[name=testNum]").attr("data-id");
    var val = $("input[name=testNum]").val();
    if(arr1[val]){          //如果知道题存在答案
        arr1[val]={"id":id,"myanswer":str}
    }else{                  //不存在则push进去
        arr1.push({"id":id,"myanswer":str})
    }
    localarr[id]=str;
   // console.log(localarr);
    var arr=testdata;
    var index;
    if(num=="0"){
        //console.log(val)
        if(val==0){
            return false;
        }
        $(".next").html("下一题");
        index = Number(val)-1;
        $("input[name=testNum]").attr("data-id",arr[index].stemid);
        $("input[name=testNum]").val(index);
        $('.sz_color_a').html(index+1)
    }else{
        if(val==arr.length-1){
            return false;
        }
        $(".prev").show();
        index = Number(val)+1;
        $("input[name=testNum]").val(index);
        $("input[name=testNum]").attr("data-id",arr[index].stemid);
        $(".sz_color_a").html(index+1)
        if(index==arr.length-1){
            //console.log(index)
            $(".next").html("提交");
        }
    }
    var test = arr[index];
    var type;
    switch(test.type){
        case "0":
            type="单选题"
            break;
        case "1":
            type="多选题"
            break;
        case "2":
            type="判断题"
            break;
        default:
            //alert("题型错误")
    }
    $(".sz_bord").html(index+1+".【"+type+"】"+test.content);
    $(".tpoint").html(test.stempoint);
    var testmain="";
    if(test.type=="1"){
        for(var j =0;j<test.options.length;j++){
            if(test.myanswer){var tj = test.myanswer.indexOf(test.options[j].code)!=-1
                var checkd=tj?"checked='checked'":""}
            testmain += "<div class='testoption'><input type='checkbox' class='option-input radio' name='text'"+checkd+" value='"+test.options[j].code+"'><div class='optionmain'>"+test.options[j].code+":"+test.options[j].content+"</div></div>"
        }
    }else{
        for(var k =0;k<test.options.length;k++){
          var testcode;
          if(test.type==='2'){
            testcode=''
          }else if(test.type==='0'){
            testcode=test.options[k].code+':'
          }
            if(test.myanswer){var tj = test.myanswer.indexOf(test.options[k].code)!=-1
                var checkd=tj?"checked='checked'":""}
            testmain += "<div class='testoption'><input type='radio' class='option-input radio' name='text'"+checkd+" value='"+test.options[k].code+"'><div class='optionmain'>"+testcode+test.options[k].content+"</div></div>"
        }
    }
    $(".sz_abcd").html(testmain)
    var localid = arr[index].stemid;
    if(localarr[localid]){      //取出答案
        if(localarr[localid].length>1){     //如果大于1，则是多选，进行字符串分割
            var fgarr = (localarr[localid]).split(",");
            for(var a =0;a<fgarr.length;a++){ //遍历
                $("input[name=text]").each(function () {    //遍历表单 赋值
                    var $val = $(this).val();
                    if($val==fgarr[a]){
                        $(this).attr("checked",true)
                    }else{
                        //alert("没有选项")
                    }
                })
            }
        }else{
            $("input[name=text]").each(function () {    //遍历表单 赋值
                var $val = $(this).val();
                if($val==localarr[localid]){
                    $(this).attr("checked",true)
                }else{
                    //alert("没有选项")
                }
            })
        }

    }

}
function save(num) {
  if($('.save').hasClass('disabled')){
    return false;
  }
    if(num==1){
        var str = "";
        if($("input[name=text]:checked").length<1&&num!="0"){ //判断是否选择
            alert({
                title:"",
                content:"请选择答案"
            })
            return false;
        }
        $("input[name=text]:checked").each(function () {
            if(str){
                str=str+","+$(this).val();
            }else{
                str+=$(this).val();
            }

        })
        var id=$("input[name=testNum]").attr("data-id");
        arr1.push({"id":id,"myanswer":str})
        $('.save').html('保存中');
        $('.save').addClass('disabled');
    }
    var userid = localStorage.userid;
    var schoolid = localStorage.schoolid;
    var examid = window.location.search.substr(1);
    var test = {"userid":userid,"examid":examid,"isdone":num,"data":arr1}  /*   1是保存 2是提交  */
    //console.log(JSON.stringify(data))
    //console.log(arr1)
    $.ajax({
        url: apiUrl + "myexamsubmit",//服务器请求地址
        type: 'post',//提交方式 GET POST PUT DELETE
       data:{"key":key,"schoolid":schoolid,"data":JSON.stringify(test)},//传给服务器的值
        dataType: 'json',
        success:function (data) {
          if (data.httpcode == '600') {
            alert({
              title:'提示',
              content:'会话超时<br>请返回微信聊天窗口重新进入',
              canel:function(){
                  $("#dialog2").remove();
                  WeixinJSBridge.call('closeWindow');
              }
            })
          }
            if(num==2&&data.code=="100"){
                location.href="my.html"
            }else if(num==1&&data.code=="100"){
                toast(data.msg)
            }
            if(num==2){
              $('.next').html('提交')
              $('.next').removeClass('disabled');
            }else if(num == 1){
              $('.save').html('保存进度');
              $('.save').removeClass('disabled');
            }
        }
    })
}
