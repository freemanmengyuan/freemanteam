/*
  2016.5.20
  微信接口开发(扫一扫功能实现)
*/
(function(){
  var sdkUrl = window.location.href;
  //var sdkappid="wx8b2659e6c0bd4c21",sdktimestamp,sdknonceStr,sdksignature;
  var sdkappid="wxc1779e7769be2fad";

  if(sdkUrl.indexOf("#")!=-1){  //防止锚点
    sdkUrl = sdkUrl.split("#")[0]
  }

  $.ajax({
    type:"POST",
    url:apiUrl+"getticket",
    data:{"sdkUrl":sdkUrl},
    dataType:"json",
    success:function(data){
      if(data.httpcode=="200"){

        sdktimestamp = data.result.timestamp;
        sdknonceStr = data.result.noncestr;
        sdksignature = data.result.signature;

        wx.config({     //配置微信jssdk
          debug: false,
          //appId: sdkappid,
		  appid: sdkappid,
          timestamp: sdktimestamp,
          nonceStr: sdknonceStr,
          signature: sdksignature,
          jsApiList:['scanQRCode']
        });

        wx.ready(function(){  //加载方法
          document.querySelector("#test").addEventListener("click",function(){  //添加click事件
            wx.scanQRCode();  //调用默认扫描方法，扫描结果由微信处理

            // wx.scanQRCode({  //调用微信第二次返回二维码的值
            //    needResult: 1,
            //    desc: 'scanQRCode desc',
            //    success: function (res) {
            //
            //    }
            //  })
          })
        })
      }else{
        //错误信息
        alert("error")
      }
    },
    error:function(msg){
      //错误信息
    }
  })
})()
