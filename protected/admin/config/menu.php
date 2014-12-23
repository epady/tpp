<?php
/**
 * 
 * 后台菜单配置
 * 
 */
$menu['Admin'] = array(
	'index'=>array(
		'name'=>'首页',
		'submenu'=>array(
			'后台首页'=>$this->createUrl('/site/welcome'),
			'关于我们'=>$this->createUrl('/about/index'),
		)
	),

	'bases' => array(
		'name' => '基础设置',
		'submenu' => array(
			'地区管理' => $this->createUrl('/area/index'),
			'机构管理' => $this->createUrl('/company/index'),
			'常用分类' => $this->createUrl('/usecategory/index'),
			'线路管理' => $this->createUrl('/line/index'),
			'基地管理' => $this->createUrl('/base/index'),
			'活动管理' => $this->createUrl('/activity/index'),
		),
	),

	'member' => array(
		'name' => '用户管理',
		'submenu' => array(
			'用户列表' => $this->createUrl('/member/index'),
			'用户积分' => $this->createUrl('/memberIntegral/index'),
		),
	),


	'sheyingshi' => array(
		'name' => '摄影师管理',
		'submenu' => array(
			'摄影师列表' => $this->createUrl('/shop/index'),
			'添加摄影师' => $this->createUrl('/shop/create'),
			'服务管理' => $this->createUrl('/service/index'),
			'案例管理' => $this->createUrl('/cases/index'),
			'档期管理' => $this->createUrl('/schedule/index'),
			'评论管理' => $this->createUrl('/comment/index'),
		),
	),




	'order' => array(
		'name' => '订单管理',
		'submenu' => array(
			'订单列表' => $this->createUrl('/order/index'),		
			'订单日志' => $this->createUrl('/log/index'),	
		)
	),




	'global'=>array(
		'name'=>'系统',
		'submenu'=>array(
            '订单设置'=>$this->createUrl('/config/price'),
            '短信设置'=>$this->createUrl('/config/sms'),
            'APP版本'=>$this->createUrl('/config/app'),
            '注册赠送设置'=>$this->createUrl('/config/register'),
			'后台用户'=>$this->createUrl('/administrator/index'),
			//'权限管理'=>$this->createUrl('/rights'),
			//'代码生成器'=>$this->createUrl('gii/adminModel/index'),
		)
	),
);

$menu['pay'] = array(
	'index'=>array(
		'name'=>'首页',
		'submenu'=>array(
			'后台首页'=>$this->createUrl('/site/welcome'),
			'个人资料' => $this->createUrl('/site/profile'),
		)
	),

	'paying' => array(
		'name' => '资金管理',
		'submenu' => array(
			'资金列表' => $this->createUrl('/memberMoney/index'),
			'我要充值' => $this->createUrl('/memberMoney/create'),
		),

	),
);


return $menu;