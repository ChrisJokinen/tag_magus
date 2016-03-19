<?php
/*
	Each modules should have an index.php file. This file describes the modules and states what files
	are needed to build and maintain the modules. The framework will validate the modules has changed
	or not and prompt the admin for action.
*/

	$settings = array(
		'name' => 'users',
		'description' => 'Module for user login/logout',
		'files' => array(
			'globals' => 'globals.php', // global variables
			'install' => 'install.php', // install module script
			'run'     => 'run.php', // execution of module script
			'update'  => '', // update of module script
			'delete'  => ''  // delete module script
		),
		'dependancies' => array(''), // list of modules that this one is dependant on
	);

?>