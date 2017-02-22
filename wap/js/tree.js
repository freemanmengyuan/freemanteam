/**
 * Created by Administrator on 2016/4/20.
 * 图片展示
 */
var slider = $('.bxslider').bxSlider({
    controls:false,
    pager:false,
    onSlideBefore:function (slider,oldIndex,newIndex) {
        //alert("12222")
        var str ="";
        var src1 =$(".bxslider li").eq(1).children("img").attr("src");
        var src="";
        if(src1.indexOf("_1.")!=-1){
            str="_"+(Number(newIndex)+1)+"."
            src = src1.replace("_1.",str);
        }else{
            str=(Number(newIndex)+1)+".png"
            src = src1.replace("1.png",str)
        }
        $(".bxslider li").eq(Number(newIndex)+1).children("img").attr("src",src);
    },
    onSlideAfter:function (slider,oldIndex,newIndex) { //变化之后的回掉函数
        $("input[type=range]").val(Number(newIndex)+1);
        $("input[name=imgNum]").val(Number(newIndex)+1);    //newIndex是字符串 需要转化为数字
    }
});
$('input[type=range]').change(function () {
    var index = this.value;
    slider.goToSlide(index*1-1);
})
$('input[name=imgNum]').change(function () {
    var index = this.value;
    var maxIndex = $('input[type=range]').attr('max');
    var minIndex = $('input[type=range]').attr('min');
    if(Number(index)){
        index = parseInt(Number(index));
        if(index>maxIndex){
            $("input[name=imgNum]").val(maxIndex);
            slider.goToSlide(maxIndex-1);
        }else if(index<minIndex){
            $("input[name=imgNum]").val(1);
            slider.goToSlide(0);
        }else{
            slider.goToSlide(index*1-1);
        }
    }else {
        $("input[name=imgNum]").val(1);
        slider.goToSlide(0);
    }
})
