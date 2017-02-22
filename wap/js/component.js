/*
* vue 载入组件
*/

Vue.component('load',{
  template:'<div class="spinner">'+
            '<div class="double-bounce1"></div>'+
            '<div class="double-bounce2"></div>'+
            '<div class="sinnerLoad">载入中</div>'+
            '</div>'
})

/*
* vue 暂无信息
*/

Vue.component('nomsg',{
  props:['msg'],
  template:'<div class="listLoad">{{msg}}</div>'
})

/*
* 防止二次点击的 按钮
*/

Vue.component('resubmit',{
  props:{
  msg:'msg',
  btnclass:{
    type:Object,
    default:function(){
      return {}
    }
  },
  retrue:'retrue'
  },
  template:"<button :class='btnclass'>{{msg}}</button>",
  watch:{
    'retrue':function(val,oldVal){
      if(val){
        this.msg=this.msg.replace("中", "")
      }else{
        this.msg+='中'
      }
    }
  }
})

/*
  过滤器 课程列表增加外网地址
*/

Vue.filter('wip', function (value) {
  var wip = localStorage.wip;
  if(value){
    return wip+value
  }else{
    return courseDefault
  }
})
/*
* 试题添加 对应 字母
*/
Vue.filter('numChange', function (value) {
  // `input` === `this.userInput`
  var zimu = String.fromCharCode(value+65);
  return zimu
})

/*
* 试题类型
*/

Vue.filter('testType', function (value) {
  // `input` === `this.userInput`
  var testType = "";
  if(value==="0"){
    testType="判断题"
  }else if(value ==="1") {
    testType = "单选题"
  }else if (value ==="2") {
    testType = "多选题"
  }
  return testType
})
