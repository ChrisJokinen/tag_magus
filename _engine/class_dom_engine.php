<?php
	/*
		CORE Engine
	*/
	
	class dom_engine{
		
		public $dom;
		private $html = "";
		private $glue = array();
		
		
		
		public function __construct(){
		
			$this->dom = new tag_parser( file_get_contents($_SERVER['MAIN_TEMPLATE']) );
			$this->dom->process();
			
		} // __construct()
		
		
		
		public function __destruct(){} // __destruct()
		
		
		
		public function display_page(){
		
			echo $this->dom->saveHTML();
		
		} // display_page()
		
		
		
		public function add_glue($glue){
		
			$this->glue = $glue;
			$this->set_glue();
		
		} // add_glue()
		
		
		
		public function publish($page){
			if(isset($_SERVER['PUBLISH_TO']) && !empty($_SERVER['PUBLISH_TO'])){
			
				$this->generate_output();
				//echo "Publish To: ".$_SERVER['PUBLISH_TO']."<br>";
				
				$path = $_SERVER['PUBLISH_TO'].'/'.$page;
				
				$fp = fopen($path,'w+');
				fwrite($fp, $this->html);
				fclose($fp);
				
			}
		} // publish()
		
		
		
		public function display(){
			
			$tmp = array();
			echo "<pre>".print_r($this->dom->get_prop_value('html_processed'),1)."</pre>";
			
		} // display()
		
		
		
		private function generate_output(){
			
			foreach( $this->dom->get_prop_value('html_processed') AS $key=>$obj ){
				if(get_class($obj) == 'tag'){
					switch($obj->get_prop_value('type')){
						case 'comment':
							$this->html.= html_entity_decode( $obj->get_prop_value('text'),ENT_QUOTES );
							break;
						case 'text':
							$this->html.= $obj->get_prop_value('text');
							break;
						default: // element
							$this->html.= "<".$obj->get_prop_value('name');
							foreach( $obj->attributes AS $atr=>$val ){
								if($val=='EMPTY-ATTRIBUTE'){
									$this->html.= " ".$atr;
								}
								else{
									$this->html.= ' '.$atr.'="'.$val.'"';
								}
								
								// Check that href and scr files exist in publish target and are up to date
								$atr = strtolower($atr);
								if( $atr=='src' || $atr=='href' ){
									$this->verify_file($val);
								}
							}
							$this->html.= ">";
					}
				}
				else{
					$this->html.= $obj->src;
				}
			}
		
		} // generate_output()
		
		
		private function verify_file($fp){
			
			$copy = false;
			
			// I just want valid local paths
			$host = parse_url($fp, PHP_URL_HOST);
			
			if(!empty($fp) && ( empty($host) || $_SERVER['SERVER_NAME']==$host ) && substr($fp,0,1)!='#' ){
				
				// clean path for local web development configurations that do not have a host alias
				$remove = explode(DIRECTORY_SEPARATOR,SITE_PATH);
				$fp = explode("/",parse_url($fp, PHP_URL_PATH));
				
				$tmp = '';
				$dir = array();
				$i=0;
				foreach( $fp AS $p ){
					if( !in_array($p,$remove) && !empty($p) ){
						if($i>0){
							$tmp.="/";
						}
						$tmp.=$p;
						$i++;
						
						if( is_dir($tmp) ){
							$dir[]=$p;
						}
					}
				}
				$fp 	= str_replace("/",DIRECTORY_SEPARATOR,SITE_PATH."/".$tmp);
				$pub_fp	= str_replace("/",DIRECTORY_SEPARATOR,$_SERVER['PUBLISH_TO']."/".$tmp);
				
				
				// check that file exist in current directory.
				if( file_exists($fp) ){
					
					// make source hash
					$src_hash = hash_file('md5', $fp);
					
					// check that file exist in publish directory
					if( file_exists($pub_fp) ){
						// check that files match.
						$pub_hash = hash_file('md5', $pub_fp);
						
						if($src_hash!=$pub_hash){
							$copy = true;
						}
					}
					else{
						$copy = true;
					}
					
					if($copy){
						// make missing directories
						$pub_dir = $_SERVER['PUBLISH_TO'];
						foreach($dir AS $d){
							$pub_dir = $pub_dir.DIRECTORY_SEPARATOR.$d;
							if( !is_dir($pub_dir) ){
								mkdir($pub_dir,'0644');
							}
						}
						
						// copy file
						$content = file_get_contents($fp);
						$file = fopen($pub_fp,'w+'); // overwrite if out of sync
						if( (fwrite($file,$content)) !== false){
							echo "Copied: ".$fp." to ".$pub_fp."<br>";
						}
						else{
							echo "Failed to copy: ".$fp." to ".$pub_fp."<br>";
						}
						fclose($file);
						
						$copy = false;
					}
				}
				// else ignore
			}
			
		} // verify_file()
		
		
		private function set_glue(){ 
			
			foreach($this->glue AS $html_id=>$data){ 
				
				foreach($data AS $key=>$value){
					
					if( $key == 'path' ){
						
						$fragment = new tag_parser( file_get_contents($value) );
						$fragment->process();
						$this->insert_into_dom( $html_id, $fragment->get_prop_value('html_processed') );
						
						//echo "<pre>".print_r($fragment,1)."</pre>";
					}
					else if( $key == 'code' ){
						
						$code = new stdClass;
						$code->src = file_get_contents($value);
						$this->insert_into_dom( $html_id, $code, 'code' );
						
					}
				}
			}
		
		} // set_glue()
		
		
		private function insert_into_dom( $id, $obj_array, $type='tag' ){
			
			// merge arrays, being careful not to overwrite and inserting after a specific point.
			
			$tmp = array();
			foreach($this->dom->get_prop_value('html_processed') AS $tag){
				
				$attr = $tag->get_prop_value('attributes');
				foreach($attr as $key=>$val){
					
					if($key=='id' && $val==$id){
						
						$tmp[] = $tag;
						
						if($type == 'code'){
							$tmp[] = $obj_array;
						}
						else{  //$type == 'tag'
							foreach($obj_array AS $new_tag){
							
								$tmp[] = $new_tag;
							
							}
						}
						continue 2;
						
					}
					
				}
				$tmp[] = $tag;
			}
			$this->dom->set_prop_value('html_processed',$tmp);
		} // insert_into_dom()
		
	} // dom_engine
?>