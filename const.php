<?php

namespace Techy;

/**
 * Directories
 */
define( 'ROOT_WWW', ROOT .'www/' );

define( 'APP_DIR', ROOT .'app/' );
define( 'APPLICATION_DIR', APP_DIR .'Application/' );
define( 'CONFIG_DIR', ROOT .'config/' );
define( 'CONFIG_PLATFORMS_DIR', CONFIG_DIR .'platforms/' );
define( 'TESTS_DIR', APP_DIR .'Test/' );

define( 'EXTERNAL_DIR', ROOT .'external/' );
define( 'LOCALES_DIR', ROOT .'i18n/' );
define( 'SQL_DIR', ROOT .'database/' );

define( 'STATIC_DIR', ROOT .'static/' );
define( 'STATIC_COMPILED_DIR', STATIC_DIR .'compiled/' );

define( 'I18N_DIR', 'i18n/' );
define( 'LESS_DIR', 'less/' );
define( 'CSS_DIR', 'css/' );
define( 'JS_DIR', 'js/' );
define( 'ASSETS_DIR', 'assets/' );
define( 'ASSETS_LESS_DIR', 'assets/less/' );
define( 'ASSETS_JS_DIR', 'assets/js/' );

define( 'LOCALES_JS_DIR', ROOT_WWW . JS_DIR .'i18n/' );

define( 'CONFIG_LOCALES', 'locales' );

/**
 * ReCaptcha
 * https://www.google.com/recaptcha/admin/site?siteid=
 */
define( 'RECAPTCHA_PUBLIC_KEY',  '' );
define( 'RECAPTCHA_PRIVATE_KEY', '' );
define( 'RECAPTCHA_THEME', 'clean' );

/**
 * Constants
 */
const ALPHABET = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
