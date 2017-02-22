(function(){
  var urldata =window.location.href;
  $.ajax({
    url:apiUrl+"getticket",
    data:{"key":key,"sdkUrl":urldata},
    type:"POST",
    success:function(data){
      var data = JSON.parse(data);
      if(data.httpcode=="200"){
        wx.config({
            debug: false,
            appId: 'wxc1779e7769be2fad', //企业号id
            timestamp: data.result.timestamp,
            nonceStr: data.result.noncestr,
            signature: data.result.signature,
            jsApiList: ['scanQRCode']
          });
        wx.error(function (res) {
          console.log(res.errMsg);
        });
      }
    }
  })
})()
