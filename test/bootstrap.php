<?php
/**
 * Test bootstrap
 *
 * This file includes the autoloading mechanism.
 *
 * This file is part of Mbsic. Please refer to LICENSE.txt for
 * license information.
 * @copyright 2016 Jack126Guy
 * @license MIT
 */

/**
 * Directory for autoloaded classes, without a trailing slash
 */
define('MBSIC_AL_DIR', dirname(__FILE__) . '/../lib');

/**
 * Autoload a class from the autoload directory by including a file.
 * The file path is of the form `[class name].php` with underscores replaced by
 * slashes. Letter case is preserved.
 * @param string $class Class name
 */
function mbsic_autoload($class) {
	$path = MBSIC_AL_DIR . '/' . str_replace(array('_'), '/', $class) . '.php';
	if(file_exists($path)) {
		require_once $path;
		return;
	}
}

if(function_exists('spl_autoload_register')) {
	spl_autoload_register('mbsic_autoload');
} else {
	if(!function_exists('__autoload')) {
		/**
		 * Autoload a class.
		 * @param string $class Class name
		 * @see mbsic_autoload() mbsic_autoload()
		 */
		function __autoload($class) {
			mbsic_autoload($class);
		}
	} else {
		trigger_error(
			'Could not register Mbsic autoload function.'
			. 'Please upgrade PHP or modify the other __autoload() function.',
			E_USER_ERROR
		);
	}
}