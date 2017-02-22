<?php
//启用查询字符串
//application/config.php
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';

//定义默认控制器
//application/config/routes.php
$route['default_controller'] = 'blog';

//使用构造方法
class Blog extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); //方法重载
    }
}

//渲染视图  分配数据
$data = new Someclass();
$this->load->view('blogview', $data); //对象
$data = array(
    'title' => 'My Title',
    'heading' => 'My Heading',
    'message' => 'My Message'
);
$data['page_title'] = 'Your title';
$this->load->view('header');
$this->load->view('menu');
$this->load->view('content', $data); //数组

