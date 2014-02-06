<?php

$config = array (
	'get' => array(
		'' => array(
			0 => '\Techy\Application\Admin\Controller\Main',
			1 => 'main',
			2 => '',
			3 => array(
			),
			4 => 'Native',
		),
		'admin/admins' => array(
			0 => '\Techy\Application\Admin\Controller\User\Admins',
			1 => 'main',
			2 => 'admin/admins',
			3 => array(
			),
			4 => 'Native',
		),
		'admin/banners' => array(
			0 => '\Techy\Application\Admin\Controller\User\Banners',
			1 => 'main',
			2 => 'admin/banners',
			3 => array(
			),
			4 => 'Native',
		),
		'admin/books' => array(
			0 => '\Techy\Application\Admin\Controller\User\Books',
			1 => 'new',
			2 => 'admin/books',
			3 => array(
			),
			4 => 'Native',
		),
		'admin/books/<\d+>' => array(
			0 => '\Techy\Application\Admin\Controller\User\Books',
			1 => 'edit',
			2 => 'admin/books/<\d+>',
			3 => array(
				0 => 'copy_id',
			),
			4 => 'Native',
		),
		'admin/home' => array(
			0 => '\Techy\Application\Admin\Controller\User\Home',
			1 => 'home',
			2 => 'admin/home',
			3 => array(
			),
			4 => 'Native',
		),
		'admin/signout' => array(
			0 => '\Techy\Application\Admin\Controller\User\Home',
			1 => 'signout',
			2 => 'admin/signout',
			3 => array(
			),
			4 => 'Native',
		),
		'admin/users' => array(
			0 => '\Techy\Application\Admin\Controller\User\Users',
			1 => 'main',
			2 => 'admin/users',
			3 => array(
			),
			4 => 'Native',
		),
		'auth/recovery' => array(
			0 => '\Techy\Application\Admin\Controller\Authorization\PasswordRecovery',
			1 => 'passRecovery',
			2 => 'auth/recovery',
			3 => array(
			),
			4 => 'Native',
		),
		'auth/recovery/<\d+>/<\w+>' => array(
			0 => '\Techy\Application\Admin\Controller\Authorization\PasswordRecovery',
			1 => 'newPassword',
			2 => 'auth/recovery/<\d+>/<\w+>',
			3 => array(
				0 => 'user_id',
				1 => 'code',
			),
			4 => 'Native',
		),
	),
	'post' => array(
		'admin/admins' => array(
			0 => '\Techy\Application\Admin\Controller\User\Admins',
			1 => 'create',
			2 => 'admin/admins',
			3 => array(
			),
			4 => 'Json',
		),
		'admin/authors' => array(
			0 => '\Techy\Application\Admin\Controller\User\Authors',
			1 => 'newSubmit',
			2 => 'admin/authors',
			3 => array(
			),
			4 => 'Json',
		),
		'admin/banners' => array(
			0 => '\Techy\Application\Admin\Controller\User\Banners',
			1 => 'newSubmit',
			2 => 'admin/banners',
			3 => array(
			),
			4 => 'Json',
		),
		'admin/banners/<\d+>/remove' => array(
			0 => '\Techy\Application\Admin\Controller\User\Banners',
			1 => 'removePhoto',
			2 => 'admin/banners/<\d+>/remove',
			3 => array(
				0 => 'banner_id',
			),
			4 => 'Json',
		),
		'admin/books' => array(
			0 => '\Techy\Application\Admin\Controller\User\Books',
			1 => 'newSubmit',
			2 => 'admin/books',
			3 => array(
			),
			4 => 'Json',
		),
		'admin/books/<\d+>' => array(
			0 => '\Techy\Application\Admin\Controller\User\Books',
			1 => 'editSubmit',
			2 => 'admin/books/<\d+>',
			3 => array(
				0 => 'copy_id',
			),
			4 => 'Json',
		),
		'admin/users' => array(
			0 => '\Techy\Application\Admin\Controller\User\Users',
			1 => 'create',
			2 => 'admin/users',
			3 => array(
			),
			4 => 'Json',
		),
		'auth/recovery' => array(
			0 => '\Techy\Application\Admin\Controller\Authorization\PasswordRecovery',
			1 => 'passRecoverySubmit',
			2 => 'auth/recovery',
			3 => array(
			),
			4 => 'Json',
		),
		'auth/recovery/<\d+>/<\w+>' => array(
			0 => '\Techy\Application\Admin\Controller\Authorization\PasswordRecovery',
			1 => 'newPasswordSubmit',
			2 => 'auth/recovery/<\d+>/<\w+>',
			3 => array(
				0 => 'user_id',
				1 => 'code',
			),
			4 => 'Json',
		),
		'auth/signin' => array(
			0 => '\Techy\Application\Admin\Controller\Authorization\SignIn',
			1 => 'signInSubmit',
			2 => 'auth/signin',
			3 => array(
			),
			4 => 'Json',
		),
	),
);