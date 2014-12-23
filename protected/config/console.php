<?php


return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'淘拍拍',

	// preloading 'log' component
	'preload'=>array('log'),

	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),	

	// application components
	'components'=>array(
		
		// 数据库设置
		'db'=> require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database.php'),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);