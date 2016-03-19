<?php
	
	class database{
		
		protected $dbh = NULL;
		protected $resultset = array();
		protected $error = '';
		protected $last_insert_id = null;
		
		// default is the system login, remove later
		private $params = array(); 
		
		public function __construct( $params = array() ){
			
			if( count($params)>0 ){
				$this->params = $params;
			}
			
			$dsn = $this->params[0].':host='.$this->params[1].';dbname='.$this->params[2];
			$user = $this->params[3];
			$pass = $this->params[4];
			
			$opts = array();
			if( isset($this->params[5]) && count($this->params[5])>0 ){
				$opts = $this->params[5];
			}
			
			try {
				if(empty($opts)){
					$this->dbh = new PDO($dsn, $user, $pass);
				}
				else{
					$this->dbh = new PDO($dsn, $user, $pass, $opts);
				}
			}
			catch (PDOException $e) {
				
				$this->error = 'Connection failed: ' . $e->getMessage().' - '.$dsn;
			}
			
		} // __construct()
		
		
		
		public function __destruct(){
		
		
		} // __destruct()
		
		
		
		protected function log_system_message($type,$message){
			
			$dbh = $this->dbh;
			//echo "<pre>DBH: ".print_r($dbh,1)."</pre>";
			
			$sql = '
INSERT INTO
	tag_magus.system_logs
(
	system_log_type,
	system_log_message
)
VALUES
(
	:system_log_type,
	:system_log_message
)';
			
			$sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			if($sth->execute(array(':system_log_type' => $type, ':system_log_message' => $message))){
				$this->last_insert_id = $dbh->lastInsertId();
			}
			else{
				echo $this->format_db_error($sth->errorInfo());
			}
		
		} // log_system_message()
		
		
		protected function is_error(){
			$rtn = false;
			if( !empty($this->error) ){
				$rtn = true;
			}
			return $rtn;
		} // is_error()
		
		
		
		protected function format_db_error($arr){
			$msg = "";
			if(!empty($arr)){
				$msg = "<div class='tag_magus_error'>";
				$msg.= "<span>SQLSTATE: </span>".$arr[0]."<br>";
				$msg.= "<span>CODE: </span>".$arr[1]."<br>";
				$msg.= "<span>MESSAGE: </span>".$arr[2];
				$msg.= "</div>";
			}
			return $msg;
		} // format_db_error()
		
		
		
		protected function set_prop($prop,$val){
			if(property_exists($this,$prop)){
				$this->$prop = $val;
			}
		} // set_prop()
		
	}// database
?>