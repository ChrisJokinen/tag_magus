<?php
	session_start();
	
	date_default_timezone_set('America/Chicago');
	
	
	DEFINE('SITE_PATH',		'./' ); // dirname(__FILE__)
	
	DEFINE('ENGINE_PATH',	SITE_PATH.'_engine/');
	DEFINE('RESOURCE_PATH',	SITE_PATH.'resources/');
	DEFINE('MODULE_PATH',	SITE_PATH.'modules/');
	
	DEFINE('CSS_PATH',		RESOURCE_PATH.'css/');
	DEFINE('IMAGE_PATH',	RESOURCE_PATH.'images/');
	DEFINE('INC_PATH',		RESOURCE_PATH.'inc/');
	DEFINE('JS_PATH',		RESOURCE_PATH.'js/');
	DEFINE('TEMPLATE_PATH',	RESOURCE_PATH.'templates/');
	
	
	$_SERVER['MAIN_TEMPLATE'] = TEMPLATE_PATH."main_template.html";
	
	
	require_once(ENGINE_PATH.'_config.php');
	require_once(ENGINE_PATH.'class_tags.php');
	require_once(ENGINE_PATH.'class_tag_parser.php');
	require_once(ENGINE_PATH.'class_dom_engine.php');
	require_once(ENGINE_PATH.'class_page_maker.php');
	require_once(ENGINE_PATH.'class_database.php');
	require_once(ENGINE_PATH.'class_modules.php');
	
	
	$_SERVER['PUBLISH_TO'] = $_SERVER['DOCUMENT_ROOT'].'target';
	
	
	/*
		test modules for missing and changed.
	*/
	modules();
	
	/* 
		create missing pages according to the $site_map array() 
	*/
	$site_map = array(
		'index.php',
	);
	
	create_pages($site_map);
?>