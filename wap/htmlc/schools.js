/**
 * Created by Administrator on 2016/4/14.
 */
$.ajax({
    url:apiUrl+"school",//服务器请求地址
    type:'get',//提交方式 GET POST PUT DELETE
    data: {'key':key},//传给服务器的值
    dataType:'json',
    success:function(result) {
        var data = result;
       // console.log(data)
        // console.log(data)
        // console.log(JSON.stringify(data))
        for( var key in data.result){
                var html = "<ul><a name='"+key+"' class='title'>"+key+"</a>";
                for(var i=0;i<data.result[key].length;i++){
                    html+="	<li><a href='javascript:;' data-id='"+ data.result[key][i].schoolid+"'>"+data.result[key][i].schoolname+"</a> </li>"
                }
                html+="</ul>"
                //console.log(html)
                var selector = key.toLocaleLowerCase()
            $("#"+selector).html(html);
            }
        },
    error:function (textStatus, errorThrown) {
        
    }
});
$("#sz_tw").on('click','a',function(){
    var storage = window.localStorage;
    var schoolid = $(this).attr("data-id");  //读取schoolid值
    var schoolname = $(this).html();//读取学校名称
    storage.setItem("schoolid",schoolid);   //将id存入本地
    storage.setItem("schoolname",schoolname);
    if(schoolid){
        window.location.href=loginAddress;
    }
})