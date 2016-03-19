<?php
	function modules(){
		$mods = new tag_modules();
		$mods->process();
	}
	
	
	/*
		Class to manage modules
	*/
	class tag_modules extends database{
		
		/*
			modules_known[] = array(
				module_name = '',
				module_path = '',
				module_created = '',
				module_files[] = array(
					file_name,
					file_hash,
					file_created,
				),
			);
		*/
		
		private $modules_known = array(); // holds modules already in database.
		private $modules_in_directory = array(); // holds modules already in database.
		private $modules_new = array(); // holds modules added to system, but not in framework.
		private $modules_update = array(); // holds modules that may need to be updated.
		private $modules_broken = array(); // holds modules in database and missing sources.
		
		
		
		public function __construct(){
			global $db_user;
			parent::__construct($db_user['module']);
			
		} // __construct()
		
		
		
		public function __destruct(){
		
		
		} // __destruct()
		
		
		
		public function process(){
			$this->get_modules_known();
			$this->get_modules_in_directory();
			$this->split_directory_modules_into_new_and_update();
			$this->add_the_new_modules();
			$this->check_on_modules_needing_update();
			$this->update_modules();
		} // process()
		
		
		
		public function set_message($msg){
			$this->log_system_message('error',$msg);
		} // set_message()
		
		
		
		private function get_modules_known(){
			$dbh = $this->dbh;
			
			$sql = '
SELECT
	module_id,
	module_name,
	module_path,
	module_created,
	module_file_id,
	module_file_name,
	module_file_hash,
	module_file_created,
	module_file_modified
FROM 
	tag_magus.modules AS m
JOIN
	tag_magus.modules_files AS mf USING(module_id)
ORDER BY
	m.module_name, mf.module_file_name
';
			
			$ps = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			if($ps->execute()){
				while ($row = $ps->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
				
					$this->modules_known['module_name']['module_path']               = $row['module_path'];
					$this->modules_known['module_name']['module_created']            = $row['module_created'];
					$this->modules_known['module_name']['module_files']['module_id'] = array(
						'file_id'       => $row['module_file_id'], 
						'file_name'     => $row['module_file_name'], 
						'file_hash'     => $row['file_hash'], 
						'file_created'  => $row['module_file_hash'],
						'file_modified' => $row['module_file_modified']
					);
					
				}
			}
			else{
				echo $this->format_db_error($ps->errorInfo());
			}
			
		} // get_modules_known()
		
		
		
		private function get_modules_in_directory(){
			
			$subs = array('core','contrib','custom');
			$i = 0;
			foreach($subs AS $sd){
				$dir = MODULE_PATH.$sd;
				if($dh = opendir($dir)){
					while(($file = readdir($dh)) !== false){
						if($file != "." && $file != ".."){
							$path = $dir.'/'.$file;
							if(is_dir($path)){
								$this->modules_in_directory[] = $path;
							}
						}
					}
				}
			}
			
			// echo "<pre>".print_r($this->modules_in_directory,1)."</pre>";
		} // get_modules_in_directory()
		
		
		
		private function split_directory_modules_into_new_and_update(){
			if(!empty($this->modules_in_directory)){
				
				$tmp = array();
				if(!empty($this->modules_known)){
					
					foreach($this->modules_in_directory AS $path){
						$file = substr($path,strrpos($path,"/"));
						if(array_key_exists($file,$this->modules_known)){
							$this->modules_update[] = $path;
						}
						else{
							$tmp[] = $path;
						}
					}
					
				}
				else{
				
					$tmp = $this->modules_in_directory;
				
				}
				
				$this->modules_new = $tmp;
		
			}
			//echo "<pre>".print_r($this->modules_new,1)."</pre>";
		} // split_directory_modules_into_new_and_update()
		
		
		
		private function add_the_new_modules(){
			foreach($this->modules_new AS $path){
				$include = file($path);
				echo "<pre>".print_r($settings,1)."</pre>";
			}
		} // add_the_new_modules()
		
		
		
		private function check_on_modules_needing_update(){
			
		} // check_on_modules_needing_update()
		
		
		
		private function update_modules(){
			
		} // update_modules()
		
		
	} // modules
?>