<?php

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'淘拍拍',
	'language' => 'zh_cn',
    'charset' => 'UTF-8',
    'timeZone' => 'Asia/Shanghai',

	'theme'=>'tpp',

	'preload'=>array('log','session'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),

	'modules'=>array(
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'api',
	),

	// application components
	'components'=>array(

		// 会员登录处理模块
		'user'=>array(
			'allowAutoLogin'=>false,
		),
		
		// 地址重写
		'urlManager'=> require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'url.php'),
		
		
		// 数据库设置
		'db'=> require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database.php'),
		
		// 出错重定向页面
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),

		/**
		 * 使用EConfig 表来保存零碎数据
		 * 
		 * 操作用例：
		 * Yii::app()->config->set('name','UUTAN') 设置数据
		 * Yii::app()->config->get('name') 获取数据
		 */
		'config' => array(
            'class' => 'application.extensions.EConfig',
            'configTableName' => '{{config}}',
            'strictMode' => false,
            'autoCreateConfigTable' => false,
        ),

		// 打印日志配置
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),

		// 使用缓存 使用文件缓存
		'cache' => array(
			'class' => 'CFileCache', 
		),

	),

	// 常用设置
	'params'=> require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'params.php'),
);