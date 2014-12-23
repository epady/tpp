<?php

// 设置后台地址别名 backend
$backend= dirname(dirname(__FILE__));
$frontend=dirname($backend);
Yii::setPathOfAlias('backend', $backend);


// 注释掉前台一些定制化的配置，留下前后台共用的数据
$frontendArray=require($frontend . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'main.php');
unset($frontendArray['components']['urlManager']);
unset($frontendArray['modules']);
unset($frontendArray['components']['user']);
unset($frontendArray['components']['log']);
unset($frontendArray['theme']);

$backendArray = array(
    'basePath' => $frontend,
    'controllerPath' => $backend . DIRECTORY_SEPARATOR . 'controllers',
    'viewPath' => $backend . DIRECTORY_SEPARATOR . 'views',
    'runtimePath' => $frontend . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'admin', // 后台缓存地址 在 pro~/runtime/admin 下面,在给权限的时候尽可能少操心
    'modulePath' => $backend . DIRECTORY_SEPARATOR . 'modules',
    
    // 除前台要引用的包外，还要引用后台包数据
    'import'=>array(
        'backend.models.*',
        'backend.components.*',
    	'backend.extensions.*',
        'backend.modules.rights.*',
		'backend.modules.rights.components.*',
    ),
    
    'modules'=>array(
		
		// 引入后台权限模块，后台不是系统类的话，尽可能不用它
		'rights' => array(
            'install' => false,
		    'enableBizRule' => false,
		    'enableBizRuleData' => false,
            'appLayout' => 'backend.views.layouts.main',
            'userClass'=>'Administrator',
        ),
        
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
        ),
    ),

    'components'=>array(
        
        // 格式货列表中数据
        'format'=>array(
        	'class'=>'Formatter',
        ),
        
        // 与rights 配合使用
        'authManager'=>array(
            'class'=>'RDbAuthManager',
	    	'itemTable' => '{{admin_authitem}}',//认证项表名称 
            'itemChildTable' => '{{admin_authitemchild}}',//认证项父子关系 
            'assignmentTable' => '{{admin_authassignment}}',//认证项赋权关系 
            'rightsTable' => '{{admin_rights}}',
        ),
        
        // 后台用户登录 与rights 配合使用
        'user'=>array(
            'class'=>'RWebUser',
            'allowAutoLogin'=>true,
            'loginUrl'=> 'admin.php' ,
            'guestName'=>'游客',
            'loginUrl' => array('/site/login'),
        ),
        
        // 后台日志
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

$config = new CMap($frontendArray);
$config->mergeWith($backendArray);
$config = $config->toArray();

return $config;