<?php
	/*
		helper function
	*/
	function create_pages($pgs){
	
		if( !empty($pgs) && is_array($pgs) ){
			$make_pgs = new page_maker($pgs);
		}
		
	} // create_pages()
	
	
	/*
		class to make a new framework page
	*/
	class page_maker{
		
		private $pgs = array();
		private $src = 'resources/page_base.php';
	
		public function __construct($pgs = array()){
		
			$this->pgs = $pgs;
			$this->process();
			
		} // __construct()
		
		public function __destruct(){} // destruct()
		
		private function process(){
			
			foreach($this->pgs AS $pg){
			
				$path = SITE_PATH.'/'.$pg;
				if( !file_exists($path) ){
					$fp = fopen($path,'w+');
					fwrite($fp, file_get_contents(ENGINE_PATH.'/resources/page_base.php'));
					fclose($fp);
				}
			}
			
		} // process()
		
	} // page_maker
?>