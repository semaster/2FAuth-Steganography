<?php
if (!empty(TIMEZONE)) date_default_timezone_set(TIMEZONE);
spl_autoload_register ('autoload');
spl_autoload_register ('loadExternal');

function autoload ($className) {
	$className = str_replace( "..", "", $className );
	$className = str_replace( '\\', '/', $className );
	if ($className != 'DB') $className = strtolower($className);
	$fileName = dirname(__FILE__).'/autoload/'.$className.'.class.php';
	if (is_readable($fileName)) include_once($fileName);
}
function loadExternal ($className) {
	$class = str_replace('\\', '/', $className);
	$path = 'external/'.$class.'.php';
	if (is_readable($path)) { require_once $path; }
}
?>