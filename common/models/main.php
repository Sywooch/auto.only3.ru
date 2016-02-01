<?php
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

return array(

   'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
       'name'=>'hesus',
	
       'language' => 'ru',
   
       'charset' => 'UTF-8',
       
	'preload'=>array(),
	
      'defaultController'=>'home/login',
	
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.helpers.*',
		'application.modules.account.models.*',
		'application.components.ImageHandler.CImageHandler',
	),
      'controllerMap' => array( 
	    'redactor' => 'application.extensions.redactor.RedactorController', 
	    ),
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('5.167.170.254', '37.112.226.195','91.144.173.173', '5.167.167.172'),
			'generatorPaths'=>array(
                          'bootstrap.gii',
                 ),
		),

		'admin',
		
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			//'loginUrl' => array('home/login'),
			'loginUrl'=>array('/account/account/login'),
		),
		
		  'clientScript'=>array(
                'scriptMap' => array(
			  
                               'jquery.js' =>false,
							//   'jquery-ui.js' =>false,

                              ),
		         
                    'packages'=>array(
		            
		                'common'=> array( 
					                   'basePath' =>'start.assets', 
                                                   'css' => array('css/start.css'),
				                         'js' => array('js/start.js'),
                                                   'depends' => array('jquery'),     
		                                    ),
				        'bootstrapStatic'=> array( 
					                   'basePath' =>'application.extensions.bootstrap.assets', 
                                                   'css' => array('css/bootstrap.min.css', 'css/bootstrap-responsive.min.css', 'css/yii.css'),
				                         'js' => array('js/bootstrap.min.js'),
                                                   'depends' => array('jquery'),     
		                                    ),
                
	             ),
		  ), 
		 
		 'file'=>array(
                     'class'=>'application.extensions.cfile.CFile',
         ),

         'image'=>array(
                         'class'=>'application.extensions.image.CImageComponent',
                         'driver'=>'GD',
                         'params'=>array('directory'=>'/opt/local/bin'),
         ),
		 'ih'=>array(
                'class'=>'CImageHandler',
          ),
		// uncomment the following to enable URLs in path-format
	
		'urlManager'=>array(
			'class' => 'UrlManager',
			'urlFormat'=>'path', 
		
        
			'rules'=>array(
			    '<url:\w+>/<id:\d+>'=>'hesus/person',
		      //  's/<url:\w+>'=>'hesus/person',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				//'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
			),
			'showScriptName'=>false,
			'caseSensitive'=>false,
		),
	

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=only_quest',
			'emulatePrepare' => true,
			'username' => 'only_user',
			'password' => '96542888',
			'charset' => 'utf8',
			'schemaCachingDuration' => 3600,
		),

          'cache'=>array(
            'class'=>'system.caching.CDbCache',
            'cacheTableName' => 'cache',
            'autoCreateCacheTable' => true,
            'connectionID' => 'db',
        ), 
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'home/error',
		),
		/*
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
				),
				
			),
		), */
		
		
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'ankaniti@mail.ru',
	),
);
