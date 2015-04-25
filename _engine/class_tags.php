<?php
	/*
		Base class for tags.
	*/
	
	class tag{
	
		private $name = ""; // can be empty for text nodes, only one text node per tag
		private $type = "element"; // element, text, comment
		private $void_tag = false;
		private $closing_tag = false;
		public $attributes = array();
		private $text = '';
		
		
		public function __construct(){} // __construct()
		
		public function __destruct(){} // destruct()
		

		
		public function parse_tag($type,$tag){
			
			switch($type){
				case 'text':
					$this->name = '#'.$type;
					$this->type = $type;
					$this->void_tag = true; // treat like a void tag, no need to nest
					$this->text = $tag;
					break;
					
				case 'comment': // I may add additional logic later...
					$this->name = '#'.$type;
					$this->type = $type;
					$this->void_tag = true; // treat like a void tag, no need to nest
					$this->text = html_entity_decode($tag,ENT_QUOTES);
					break;
					
				default: // tag
					$tag = trim($this->strip_angle_brackets($tag));
					
					$this->name = $tag;
					
					$first_space = stripos($tag," ");
					if($first_space !== false){
						
						$this->name = substr($tag,0,$first_space);
						
						$attr_string = substr($tag,$first_space+1);
						//echo $attr_string."<br>";
						
						$length = strlen($attr_string);
						
						$attr_buffer = "";
						$val_buffer = "";
						$val_quotes = "double";		// double or single
						$last_val = ""; 			// to track escaped charters
						$buffer_switch = "attr"; 	// attr or val
						$in_val = false;
						$record = false;
						$has_val = false;
						
						for($i=0;$i<$length;$i++){
							if( ($attr_string[$i]=="=" && !$in_val) ){ // change to val
								$buffer_switch = "val";
								$has_val = true;
								continue;
							}
							
							switch($buffer_switch){
								case "attr":
									$attr_buffer.= $attr_string[$i]; // record attr
									//echo "ATTR: ".$attr_buffer."<br>";
									break;
								case "val":
									
									if($in_val){
										switch($val_quotes){
											case "double":
												if( $attr_string[$i]=='"' &&  $last_val!="\\"){
													$buffer_switch = "attr";
													$in_val = false;
													$record = true;
												}
												else{
													$val_buffer.= $attr_string[$i]; // record value
												}
												break;
												
											case "single":
												
												if( $attr_string[$i]=="'" &&  $last_val!="\\"){
													$buffer_switch = "attr";
													$in_val = false;
													$record = true;
												}
												else{
													$val_buffer.= $attr_string[$i]; // record value
												}
												break;
											default:
												$val_buffer.= $attr_string[$i]; // record value
										}
									}
									else if( ($attr_string[$i]=="'" || $attr_string[$i]=='"') && !$in_val){
										$in_val = true;
										$val_quotes = "double";
										if( $attr_string[$i]=="'"){
											$val_quotes = "single";
										}
									}
									else{
										$val_buffer.= $attr_string[$i]; // record value
									}
									break;
							}
							
							if($record){
								$attr_buffer = trim($attr_buffer);
								$val_buffer = trim($val_buffer);
								if($has_val){
									$this->attributes[ $attr_buffer ] = $val_buffer;
								}
								else{
									$this->attributes[ $attr_buffer ] = "EMPTY-ATTRIBUTE";
								}
								
								// reset
								$attr_buffer = "";
								$val_buffer = "";
								$has_val = false;
								$record = false;
							}
							$last_val = $attr_string[$i];
						}
						$attr_buffer = trim($attr_buffer);
						$val_buffer = trim($val_buffer);
						
						if(!empty($attr_buffer)){
							if($has_val){
								$this->attributes[ $attr_buffer ] = $val_buffer;
							}
							else{
								$this->attributes[ $attr_buffer ] = "EMPTY-ATTRIBUTE";
							}
						}
					}
					
					
					if($tag[0]=="/")
						$this->closing_tag = true;
					
					
					$this->is_void_tag();
			
			} // switch
			
			return $this;
			
		} // parse_tag()
		
		
		public function get_prop_value($prop){
		
			if(property_exists($this,$prop)){
				return $this->$prop;
			}
			
		} // get_prop_value()
		
		
		
		private function strip_angle_brackets($tag){
		
			$last = strlen($tag)-1;
			if($tag[0] == "<" && $tag[$last]==">"){
				$tag = substr($tag,1,$last-1);
			}
			return $tag;
			
		} // strip_angle_brackets()
		
		
		
		private function is_void_tag(){
		
			$this->void_tag = false;
			
			if( array_search($this->name,$_SERVER['void_tags'])!==false ){
				$this->void_tag = true;
			}
			
		} // is_void_tag()
		
	} // tag
?>