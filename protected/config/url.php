<?php 
/**
 * 
 * 地址重写规则
 * 
 * apache 重写需要使用.htaccess 配合
 * IIS 重写需要使用httpd.ini 配合
 * nginx 重写需要使用相关的conf配置
 */
return array(
	'urlFormat'=>'path',
	'urlSuffix' => '.html',
	'showScriptName' => false,
	'rules'=>array(
		'/' => 'site/index',
		'page/<name:\w+>'=>'page/index',
		'<controller:\w+>/<id:\d+>'=>'<controller>/view',
		'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
		'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
	),
);