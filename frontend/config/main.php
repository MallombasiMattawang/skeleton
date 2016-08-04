<?php

$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
	'id'					 => 'app-frontend',
	'name'					 => 'FRONT-SKELETON',
	'basePath'				 => dirname(__DIR__),
	'bootstrap'				 => ['log'],
	'controllerNamespace'	 => 'frontend\controllers',
	'components'			 => [
		/* /
		  'user'			 => [
		  'identityClass'		 => 'common\models\User',
		  'enableAutoLogin'	 => true,
		  ],
		  // */
		//*/ config sample for dektrium/user
		'user'			 => [
			'identityCookie' => [
				'name'		 => '_frontendIdentity',
				// replace with your frontend app relative to domain
				'path'		 => '/skeleton/frontend/web/',
				'httpOnly'	 => true,
			],
		],
		//*/ config sample for separate frontend session
		'session'		 => [
			'class'			 => 'yii\web\DbSession',
			'sessionTable'	 => 'yii_session',
			'name'			 => 'FRONTENDSESSID',
			'cookieParams'	 => [
				'httpOnly'	 => true,
				// replace with your frontend app relative to domain
				'path'		 => '/skeleton/frontend/web/',
			],
		],
		'log'			 => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'	 => [
				[
					'class'	 => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'errorHandler'	 => [
			'errorAction' => 'site/error',
		],
		'assetManager'	 => [
			'bundles' => [
				'dmstr\web\AdminLteAsset' => [
					'skin' => 'skin-red',
				],
			],
		],
		'urlManager'	 => [
			'class'				 => 'yii\web\UrlManager',
			'enablePrettyUrl'	 => true,
			'showScriptName'	 => false,
		],
	],
	'params'				 => $params,
	'modules'				 => [
		
		'user'	 => [
			'enableUnconfirmedLogin' => TRUE,
			//	following line will restrict access to admin controller from frontend application
			'as frontend'			 => 'dektrium\user\filters\FrontendFilter',
		],
	],
];
