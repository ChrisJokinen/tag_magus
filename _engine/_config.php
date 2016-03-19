<?php
	
	$_SERVER['tags'] = array('a','abbr','acronym','address','applet','article','aside','audio','b','bdi','bdo','big','blockquote','body','button','canvas','caption','center','cite','code','colgroup','datalist','dd','del','details','dfn','dialog','dir','div','dl','dt','em','fieldset','figcaption','figure','font','footer','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','header','html','i','iframe','input','ins','kbd','label','legend','li','main','map','mark','menu','menuitem','meter','nav','noframes','noscript','object','ol','optgroup','option','output','p','pre','progress','q','rp','rt','ruby','s','samp','script','section','select','small','span','strike','strong','style','sub','summary','sup','table','tbody','td','textarea','tfoot','th','thead','time','title','tr','tt','u','ul','var','video');
	
	$_SERVER['void_tags'] = array('area','base','basefont','br','col','!doctype','embed','hr','img','keygen','link','meta','param','source','track','wbr');
	
	/*
		permit access to external dbs
		name = general id of connection
		[name]=>array(driver, host, dbname, user, pass, options)
		
		// for membership, the subscriber logs in
		'member' => array('mysql', 'host', 'dbname',$user_name, $user_pass, array()) 
	*/
	
	
	
	$db_user = array(
		'guest' => array('mysql', 'host', 'dbname','guest_name', 'guest_pass', array()), // for anonomous, default no login
		'module' => array('mysql', 'localhost', 'tag_magus','tag_magus', 'maGus1!', array()), // for module creation and automation
		// set these as internal db roles
		//'author' => array('mysql', 'dbname', 'host','author_name', 'module_pass', opts => array()), // for content authors
		//'editor' => array('mysql', 'dbname', 'host','editor_name', 'editor_pass', opts => array()), // for content editors
		//'admin' => array('mysql', 'dbname', 'host','admin_name', 'admin_pass', opts => array()), // for sys admin
	);
?>