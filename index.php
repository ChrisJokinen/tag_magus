<?php
	require_once("_init.php");
	
	$pg = new dom_engine();
	
	$glue = array(
		'site_nav' 		=> array('path'=>'resources/inc/top_nav.html'),
		'site_content' 	=> array('code'=>'resources/code/dynamic_content.php'),
	);
	$pg->add_glue($glue);
	
	//$pg->publish(basename(__FILE__));
	echo "<pre>".print_r($pg->display(),1)."</pre>";
?>