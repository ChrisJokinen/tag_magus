<?php
	class tag_parser{
	
		private $record 			= ""; // used to buffer a tag
		private $buffer 			= ""; // used to build up text nodes
	
		private $html_source 		= "";
		private $html_array 		= array();
		private $html_processed 	= array();
		
		
		private $tree 				= array();
		private $breadcrumb 		= array();
		
		
		public function __construct($html = ""){
		
			$this->html_source = $html;
			
		} // __construct()
		
		public function __destruct(){} // destruct()
		
		
		public function process(){
		
			$this->split_source();
			$this->build_tree();
			//$this->display(); // for debug...
			
		} // process()
		
		
		public function get_prop_value($prop){
		
			if(property_exists($this,$prop)){
				return $this->$prop;
			}
			
		} // get_prop_value()
		
		
		public function set_prop_value($prop,$val){
		
			if(property_exists($this,$prop)){
				$this->$prop = $val;
			}
			
		} // set_prop_value()
		
		
		private function build_tree(){
		
			$idx = 0;
			$lvl = 1;
			$previous_void_tag = false;
			
			foreach($this->html_array AS $parsed){
				
				$node = new tag();
				$tmp = $node->parse_tag($parsed[0],$parsed[1]);
				$this->html_processed[] = $tmp;
				
				
				$name = $node->get_prop_value('name');
				
				if($previous_void_tag){
					array_pop($this->breadcrumb);
					$lvl--;
					$previous_void_tag = false;
				}
				
				
				if( $node->get_prop_value('void_tag') ){
					$this->breadcrumb[$idx] = $name;
					$lvl++;
					$idx++;
					$previous_void_tag = true;
				}
				else{
					if( $node->get_prop_value('closing_tag') ){
						$limit = $lvl;
						for( $i=0;$i<$limit;$i++ ){
							if( substr($name,1)==end($this->breadcrumb)  ){
								array_pop($this->breadcrumb);
								$lvl--;
								break;
							}
							array_pop($this->breadcrumb);
							$lvl--;
						}
					}
					else{
						// opening tag
						$cnt = count($this->breadcrumb)-1;
						
						$this->breadcrumb[$idx] = $name;
						$idx++;
						$lvl++;
					}
				}
				//echo "<pre>BC: ".print_r($this->breadcrumb,1)."</pre>";
			}
			
		} // build_tree()
		
		private function split_source(){
			
			/*
				html tags always start with a '<' then have one or more characters followed by a space or a '>' 
				without a space. I will use this to ID a tag. recording will continue until a '>' is found. This 
				will be either a void tag like <br> or a normal tag like <a href=""></a>. If a blank is immediately 
				after a '<' or no tag match is found after the first blank is found then this will be treated as
				plain text.
				
				note: 
				- attributes can contain a '<' and/or a '>', need to toggle when inside a attribute value
			*/
			
			// control
			$recording = false; 
			$inside_attribute_value = false;
			$rec_start = '<';
			$rec_stop = '>';
			$is_comment = false;
			$end_comment = false;
			
			$length = strlen($this->html_source);
			for($i=0;$i<$length;$i++){
				
				$char = $this->html_source[$i];
				//echo "<br>".$char." - #".$i."<br>";
				
				if(!$inside_attribute_value && !$is_comment){
					
					if($char == $rec_start ){
						// record until ">";
						$recording = true;
						
						// record any plain text found and reset buffer
						$this->buffer = trim($this->buffer);
						
						if(!empty($this->buffer)){
							$this->html_array[] = array('text',$this->buffer);
						}
						$this->buffer = "";
					}
					else if( $char == $rec_stop ){
						$recording = false;
						
						// add closing '>' and skip to next loop.
						if(!empty($this->record))
							$this->html_array[] = array('tag',$this->record.">");
						$this->record = "";
						continue;
					}
					
				}
				else if($is_comment){
					// look over the last 3 chars to find -->
					$end = "";
					if($i>1){
						$end = $this->html_source[($i-2)];
						$end.= $this->html_source[($i-1)];
						$end.= $this->html_source[$i];
					}
					if($end == "-->"){
						
						
						if(!empty($this->record))
							$this->html_array[] = array('comment',$this->record.">");
						
						$is_comment = false;
						$recording = false;
						$this->record = "";
						continue;
						
					}
				}
				
				if($recording){
					
					$this->record .= $char;
					
					if( $this->record=="<!--" ){
						// starting a comment. I will record comments without 
						// regard to internal tags. end at -->
						$is_comment = true;
					}
					
					
					
					//echo "REC: ".htmlentities($this->record)."<br>";
					if(!$is_comment){
						if( ($char=='"' || $char=="'") ){
							$inside_attribute_value = ($inside_attribute_value)?false:true;
						}
					}
					
				}
				else{
					$this->buffer .= $char;
					//echo "BUF: ".htmlentities($this->buffer)."<br>";
				}
			} // for
			
		} // split_source()
		
		
		public function display(){
		
			$tmp = array();
			foreach($this->html_array AS $val){
				$tmp[] = array($val[0],htmlentities($val[1]));
			}
			echo "<pre>".print_r($tmp,1)."</pre>";
			
			echo "<pre>".print_r($this->html_processed,1)."</pre>";
			
		} // display()
		
	} // tag_parser
?>