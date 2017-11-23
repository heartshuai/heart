<?php
//入口文件，任何项目必须有入口文件
//全局的配置 文件夹的路径定义
// define('CONTROLLER_PATH',__DIR__.DIRECTORY_SEPARATOR.'controllers');//存放人为定义的控制器文件夹;
define("APP_NAME","shuai");
define("APP_PATH",__DIR__);


require "core/core.php";//自己封装的mvc入口文件
App::start();//启动应用