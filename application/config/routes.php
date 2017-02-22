<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//路由开始	
//school路由
$route['school']['GET'] = 'school/schoollist';
//user登录
$route['user']['POST']='user/userlogin';
//个人资料
$route['personal']['GET']='Personal/personal_info';
//课程列表
$route['course']['GET']='course/listinfo';
//获取课程结构
$route['coursetree']['GET']='coursetree/listinfo';
//我的作业(未完成)
$route['myhomework']['GET']='myhomework/listinfo';
//我的作业(已完成)
$route['myhomeworkfinish']['GET']='myhomeworkfinish/listfinish';
//素材内页
$route['resources']['GET']='resources/listinfo';
//素材路径（图片等）
$route['resourcepath']['GET']='resourcepath/path';
//获取作业内容
$route['dohomework']['GET']='dohomework/homeworkcontent';
//做作业（未开始的）
$route['myhomeworkcontent']['GET']='myhomeworkcontent/contentlist';
//已经完成的作业
$route['myhomeworkcontentfinish']['GET']='myhomeworkcontentfinish/contentfinish';
//保存、提交作业
$route['myhomeworksubmit']['POST']='myhomeworksubmit/homeworksubmit';
//我的考试列表（未完成的）
$route['myexamwork']['GET']='myexamwork/listinfo';
//我的考试列表（完成的）
$route['myexamworkfinish']['GET']='myexamworkfinish/listfinish';
//考试内容
$route['myexamcontent']['GET']='myexamcontent/examlist';
//考试提交
$route['myexamsubmit']['POST']='myexamsubmit/examsubmit';
//解除绑定（微信）
$route['exitLogin']['GET']='exitLogin/exitWeixin';

//补充  展示实习学生详细的实习任务及作业（评分前的页面）
$route['capi/showCategoryWork']['GET']='capi/showCategoryWork/studentWork';
//补充  实习小组学生成员列表
$route['capi/studentList']['GET']='capi/studentList/listinfo';
//学生上传实习任务
$route['capi/uploadFiles']['POST']='capi/uploadFiles/uploadIndex';
