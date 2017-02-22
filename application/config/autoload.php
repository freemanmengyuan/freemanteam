<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 自动加载模型 类库 辅助函数 驱动 配置
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
*/

/*
 @$autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
*/
$autoload['packages'] = array();

/*
 @自动加载类库
 @$autoload['libraries'] = array('user_agent' => 'ua');
*/
$autoload['libraries'] = array();

/*
 @自动加载驱动
 @$autoload['drivers'] = array('cache');
 @$autoload['drivers'] = array('cache' => 'cch');
*/
$autoload['drivers'] = array();

/*
 @自动加载辅助函数
 @$autoload['helper'] = array('url', 'file');
*/
$autoload['helper'] = array();

/*
 @自动加载配置
 @$autoload['config'] = array('config1', 'config2');
*/
$autoload['config'] = array();

/*
 @$autoload['language'] = array('lang1', 'lang2');
*/
$autoload['language'] = array();

/*
//自动加载模型
@$autoload['model'] = array('first_model', 'second_model');
@$autoload['model'] = array('first_model' => 'first');
*/
$autoload['model'] = array();
