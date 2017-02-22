<?php
/**
 * Curl版本
 */
function request_by_curl($url, $datastring)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datastring);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
$base_url='http://localhost/index.php';
echo request_by_curl($base_url.'/user','password=123456&schoolid=1&username=111&key=imkey');
/*
$fp=fopen(date('YmdHis'),"a");
fwrite($fp,var_export($_POST,TRUE));
fclose($fp);
*/
//tudeCode
/*
echo $latitude=34.841302;//纬度
echo '---';
echo $longitude=113.692121;//经度
echo '<br>';
echo $tudeCode=request_by_curl($base_url.'system/encode','latitude='.$latitude.'&longitude='.$longitude);
echo '<br>';
echo request_by_curl($base_url.'system/decode','tudeCode='.$tudeCode);
echo '<br>';
*/
/*
$a[]=array('score'=>60);
$a[]=array('score'=>60);
$a[]=array('score'=>70);
var_dump($a);
foreach($a as $v)
{
	$b[]=json_encode($v);
}
$newA=array_unique($b);
var_dump($newA);
*/
//getVerifyCode post
//echo request_by_curl($base_url.'user/getVerifyCode','phone=15838146771');

//register post 433685
//echo request_by_curl($base_url.'user/register','phone=15838146771&code=030783&password=21218cca77804d2ba1922c33e0151105&recommend=11');

//userLogin post 
//echo request_by_curl($base_url.'user/userLogin','phone=15838146772&password=21218cca77804d2ba1922c33e0151105&phoneBrand=xiaomi&phoneModel=MI 1s');

//updatePassword post
//echo request_by_curl($base_url.'user/updatePassword','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&password=21218cca77804d2ba1922c33e0151105&newPassword=21218cca77804d2ba1922c33e0151105');

//userLogout post
//echo request_by_curl($base_url.'user/userLogout','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5');

//cardRecharge post
//echo request_by_curl($base_url.'card/cardRecharge','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&cardId=000&cardPassword=000');

//getHomePageData get
//http://192.168.1.88:88/index.php/khd/system/getHomePageData

//getBeel get
//http://192.168.1.88:88/index.php/khd/system/getBeel?latitude=34.826615&longitude=113.646797

//beforeOrderAlert get
//http://192.168.1.88:88/index.php/khd/system/beforeOrderAlert?userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5

//getAllCarBrand post
//echo request_by_curl($base_url.'car/getAllCarBrand','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5');

//getAllCarModel post
//echo request_by_curl($base_url.'car/getAllCarModel','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&id=1');

//getAllCarColor post
//echo request_by_curl($base_url.'car/getAllCarColor','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&id=4');

//getUserAddress post
//echo request_by_curl($base_url.'user/getUserAddress','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5');

//addCar post
//echo request_by_curl($base_url.'car/addCar','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&carId=4&carBrand=福特&carModel=萨斯&carType=拖拉机&carNumber=豫AATM888&carColor=红色&isNew=1');

//addAddress post
//echo request_by_curl($base_url.'user/addAddress','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&address=普罗旺斯中心&title=公寓&latitude=34.826615&longitude=113.646797');

//makeOrder post
//echo request_by_curl($base_url.'order/makeOrder','userId=11&phone=15838146772&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&address=大郑州普罗旺斯 &orderRemark=普罗旺斯西北角1000米&memberCarId=1&longitude=113.654567&latitude=34.836074&orderPrice=500');

//balanceAfford post
//echo request_by_curl($base_url.'pay/balanceAfford','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&orderId=1');

//scoreAfford post
//echo request_by_curl($base_url.'pay/scoreAfford','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&orderId=1');

//getOrderList post
//echo request_by_curl($base_url.'order/getOrderList','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&page=0&count=15');

//getOrderDetail post
//echo request_by_curl($base_url.'order/getOrderDetail','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&orderId=2');

//cancleOrder post
//echo request_by_curl($base_url.'order/cancleOrder','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&reason=工作人员不帅，要求退单！&orderId=60');

//commentOrder post
//echo request_by_curl($base_url.'order/commentOrder','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&comment=工作人员很帅，对这次服务很满意！&orderId=1&attitudeScore=10&speedScore=9&qualityScore=10');

//complainOrder post
//echo request_by_curl($base_url.'order/complainOrder','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&reason=工作人员流氓，我要投诉！&orderId=2&imgUrl=201412301354.jpg');

//updateNickname post
//echo request_by_curl($base_url.'user/updateNickname','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&nickName=蝌蚪先生');

//updatePhoneNumber post
//echo request_by_curl($base_url.'user/updatePhoneNumber','userId=11&newPhone=15838146772&verifyCode=433685&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5');

//accountDetail post
//echo request_by_curl($base_url.'user/accountDetail','userId=11&newPhone=15838146772&type=0&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&page=0&count=2');

//getNotification post


//recommendFriend post
//echo request_by_curl($base_url.'system/recommendFriend','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&friendPhone=15838146771');

//recommendStatus post
//echo request_by_curl($base_url.'system/recommendStatus','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5');

//recommendStatus post
//echo request_by_curl($base_url.'system/recommendStatus','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5');

//getMessageList  post
//http://192.168.1.88:88/index.php/khd/system/getMessageList?userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&page=0&count=20

//upMemberCar post
//$upArray=array('default'=>1,'carNumber'=>'豫NY5877');
//$upArray=json_encode($upArray);
//echo request_by_curl($base_url.'car/upMemberCar','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&memberCarId=1&upArray='.$upArray);

//upMemberAddress post
//$upArray=array('address'=>'新密','title'=>'家里');
//$upArray=json_encode($upArray);
//echo request_by_curl($base_url.'user/upMemberAddress','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&memberAddressId=1&upArray='.$upArray);

//alipayAfford post
//echo request_by_curl($base_url.'pay/alipayAfford','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&orderId=32&moneyTrue=9&orderNumber=1120150119161720');

//alipayRecharge post
//echo request_by_curl($base_url.'card/alipayRecharge','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&money=10&moneyTrue=9&orderNumber=1120150119161720');

//forgetPwd post
//echo request_by_curl($base_url.'user/forgetPwd','phone=15838146771');

//delMemberCar post 
//echo request_by_curl($base_url.'car/delMemberCar','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&memberCarId=1');

//delMemberCar post 
//echo request_by_curl($base_url.'car/delMemberCar','userId=11&accessToken=d47c724dc24a7c0fed5c2f946d20e9c5&memberCarId=1');

//weatherInfo get 
//http://192.168.1.88:88/index.php/khd/system/weatherInfo?city=郑州

//uploadImage post
/*
echo '<form action="http://192.168.1.88:88/index.php/khd/system/uploadImage" enctype="multipart/form-data" method="post" name="uploadFile">
<input type="text" name="userId" value=11 />
<input type="text" name="accessToken" value="d47c724dc24a7c0fed5c2f946d20e9c5" />
<input type="file" name="image" />
<input type="submit" value="submit" />
</form>';
*/
//$user_id = '964844732695160475'; 757562403425051792
//$channel_id = '3838497399388564390'; 4266092927966682640


