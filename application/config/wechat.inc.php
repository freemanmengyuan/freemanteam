<?php
/**
author:mengyuan
*/
defined('BASEPATH') OR exit('No direct script access allowed');
//企业号获取token url
$config['token_url'] = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken?';
//企业号oAuth验证接口url 获取wxuserid 获取登录信息
$config['author_url'] = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
$config['author_userid_url'] = 'https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?';
$config['userinfo_url'] = 'https://qyapi.weixin.qq.com/cgi-bin/user/get?';
$config['author_loginfo_url'] = 'https://qy.weixin.qq.com/cgi-bin/loginpage?';

//平台统一回调接口url
$config['redirect_url'] = 'http://tengxun.ejianwei.com.cn/index.php/Common/appRedirectUrl/';

//通讯录(组织)相关
$config['get_partyinfo_url'] = 'https://qyapi.weixin.qq.com/cgi-bin/department/list?';
$config['create_party_url'] = 'https://qyapi.weixin.qq.com/cgi-bin/department/create?';
$config['update_party_url'] = 'https://qyapi.weixin.qq.com/cgi-bin/department/update?';

//通讯录(用户)相关
$config['create_user_url'] = 'https://qyapi.weixin.qq.com/cgi-bin/user/create?';
$config['update_user_url'] = 'https://qyapi.weixin.qq.com/cgi-bin/user/update?';
