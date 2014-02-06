<?php

$config = array (
	'\Authorization\PasswordRecovery' => array(
		'get' => array(
			'newPassword' => array(
				0 => 'newPassword',
				1 => 'newPassword',
				2 => 'auth/recovery/<\d+>/<\w+>',
				3 => array(
					0 => 'user_id',
					1 => 'code',
				),
				4 => 'Native',
			),
			'passRecovery' => array(
				0 => 'passRecovery',
				1 => 'passRecovery',
				2 => 'auth/recovery',
				3 => array(
				),
				4 => 'Native',
			),
		),
		'post' => array(
			'newPasswordSubmit' => array(
				0 => 'newPasswordSubmit',
				1 => 'newPasswordSubmit',
				2 => 'auth/recovery/<\d+>/<\w+>',
				3 => array(
					0 => 'user_id',
					1 => 'code',
				),
				4 => 'Json',
			),
			'passRecoverySubmit' => array(
				0 => 'passRecoverySubmit',
				1 => 'passRecoverySubmit',
				2 => 'auth/recovery',
				3 => array(
				),
				4 => 'Json',
			),
		),
	),
	'\Authorization\SignIn' => array(
		'post' => array(
			'signInSubmit' => array(
				0 => 'signInSubmit',
				1 => 'signInSubmit',
				2 => 'auth/signin',
				3 => array(
				),
				4 => 'Json',
			),
		),
	),
	'\Main' => array(
		'get' => array(
			'main' => array(
				0 => 'main',
				1 => 'main',
				2 => '',
				3 => array(
				),
				4 => 'Native',
			),
		),
	),
	'\User\Admins' => array(
		'get' => array(
			'main' => array(
				0 => 'main',
				1 => 'main',
				2 => 'admin/admins',
				3 => array(
				),
				4 => 'Native',
			),
		),
		'post' => array(
			'create' => array(
				0 => 'create',
				1 => 'create',
				2 => 'admin/admins',
				3 => array(
				),
				4 => 'Json',
			),
		),
	),
	'\User\Authors' => array(
		'post' => array(
			'newSubmit' => array(
				0 => 'newSubmit',
				1 => 'newSubmit',
				2 => 'admin/authors',
				3 => array(
				),
				4 => 'Json',
			),
		),
	),
	'\User\Banners' => array(
		'get' => array(
			'main' => array(
				0 => 'main',
				1 => 'main',
				2 => 'admin/banners',
				3 => array(
				),
				4 => 'Native',
			),
		),
		'post' => array(
			'newSubmit' => array(
				0 => 'newSubmit',
				1 => 'newSubmit',
				2 => 'admin/banners',
				3 => array(
				),
				4 => 'Json',
			),
			'removePhoto' => array(
				0 => 'removePhoto',
				1 => 'removePhoto',
				2 => 'admin/banners/<\d+>/remove',
				3 => array(
					0 => 'banner_id',
				),
				4 => 'Json',
			),
		),
	),
	'\User\Books' => array(
		'get' => array(
			'new' => array(
				0 => 'new',
				1 => 'new',
				2 => 'admin/books',
				3 => array(
				),
				4 => 'Native',
			),
			'edit' => array(
				0 => 'edit',
				1 => 'edit',
				2 => 'admin/books/<\d+>',
				3 => array(
					0 => 'copy_id',
				),
				4 => 'Native',
			),
		),
		'post' => array(
			'newSubmit' => array(
				0 => 'newSubmit',
				1 => 'newSubmit',
				2 => 'admin/books',
				3 => array(
				),
				4 => 'Json',
			),
			'editSubmit' => array(
				0 => 'editSubmit',
				1 => 'editSubmit',
				2 => 'admin/books/<\d+>',
				3 => array(
					0 => 'copy_id',
				),
				4 => 'Json',
			),
		),
	),
	'\User\Home' => array(
		'get' => array(
			'home' => array(
				0 => 'home',
				1 => 'home',
				2 => 'admin/home',
				3 => array(
				),
				4 => 'Native',
			),
			'signout' => array(
				0 => 'signout',
				1 => 'signout',
				2 => 'admin/signout',
				3 => array(
				),
				4 => 'Native',
			),
		),
	),
	'\User\Users' => array(
		'get' => array(
			'main' => array(
				0 => 'main',
				1 => 'main',
				2 => 'admin/users',
				3 => array(
				),
				4 => 'Native',
			),
		),
		'post' => array(
			'create' => array(
				0 => 'create',
				1 => 'create',
				2 => 'admin/users',
				3 => array(
				),
				4 => 'Json',
			),
		),
	),
);