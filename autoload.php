<?php

spl_autoload_register(function($class) {
	$file = __DIR__.'/mpiv/'.$class.'.php';
	if(file_exists($file)) {
		require_once $file;
	}
});

?>
