<?php
	/*
		For dynamic content, an entire pages of code will be dropped in place. I will use an 
		array and randomly display one of its values.
	*/
	$content = array('one','two','three','four');
	$idx = rand(0,3);
	echo '<h2>'.$content[$idx].'</h2>';
?>