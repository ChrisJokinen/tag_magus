<?php
	/*
		base starter page
	*/
	require_once("_init.php");
	
	$pg = new dom_engine();
	
	$glue = array();
	$pg->add_glue($glue);
	
	$pg->publish(basename(__FILE__));
	echo "<pre>".print_r($pg->display(),1)."</pre>";
?>