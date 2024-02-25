<?

class Session{

	private static $session_lists = array();

	private $name;

	private $datas = array();
	private $flashs = array();

	public function __construct($name){

		if(array_key_exists($name, self::$session_lists)){
	
			exit('session key already exists');

		}

		array_push(self::$session_lists, $name);

		session_start();

		$this->name = $name;

		$this->datas = @$_SESSION[$this->name]['datas'];
		$this->flashs = @$_SESSION[$this->name]['flashs'];
		unset($_SESSION[$this->name]['flashs']);

	}

	public function reset(){
		session_destroy();
		session_start();
	}

	public function close(){
		session_destroy();
	}

	public function data($key){
		return isset($this->datas[$key]) ? $this->datas[$key] : FALSE;
	}

	public function setData($key, $value){
		$this->datas[$key] = $value;
		$_SESSION[$this->name]['datas'][$key] = $value;
	}

	public function flash($key){
		return isset($this->flashs[$key]) ? $this->flashs[$key] : FALSE;
	}

	public function setFlash($key, $value){
		$this->flashs[$key] = $value;
		$_SESSION[$this->name]['flashs'][$key] = $value;
	}

	public function keepFlash($key){
		$this->setFlash($key, $this->flash($key));
	}

}


/*

CREATE TABLE z9session(

	id		VARCHAR(32)	NOT NULL,
	access	INT(10)		unsigned,
	data	TEXT,
	PRIMARY KEY(id)

);



class Session{

}


class Session implements SessionInterface{

	private static $instance;

	private $dataKey = '__z9data__';
	private $flashKey = '__z9flash__';

	private $datas = array();
	private $flashs = array();

	private function __construct(){
		session_start();
		$this->datas = $_SESSION[$this->dataKey];
		$this->flashs = $_SESSION[$this->flashKey];
		unset($_SESSION[$this->flashKey]);
	}
	
	public static function getInstance(){
		if(!isset(self::$instance)){
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	public function reset(){
		session_destroy();
		session_start();
	}

	public function close(){
		session_destroy();
	}

	public function data($key){
		return $this->datas[$key];
	}

	public function setData($key, $value){
		$this->datas[$key] = $value;
		$_SESSION[$this->dataKey][$key] = $value;
	}

	public function flash($key){
		return $this->flashs[$key];
	}

	public function setFlash($key, $value){
		$this->flashs[$key] = $value;
		$_SESSION[$this->flashKey][$name] = $value;
	}

	public function keepFlash($key){
		$this->setFlash($key, $this->flash($name));
	}

}


class Session{

	private static $instance;
	private $dataKey = '_z9data_';
	private $flashKey = '_z9flash_';

	private $datas = array();
	private $flashs = array();

	private function __construct(){
		session_start();
		$this->datas = $_SESSION[$this->dataKey];
		$this->flashs = $_SESSION[$this->flashKey];
		unset($_SESSION[$this->flashKey]);
	}
	
	public static function getInstance(){
		if(!isset(self::$instance)){
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	public function reset(){
		session_destroy();
		session_start();
	}

	public function data($name){
		return $this->datas[$name];
	}

	public function setData($name,$value){
		if(is_array($name)){
			foreach($name as $key => $data){
				$this->setData($key,$data);
			}
		}else{
			$this->datas[$name] = $value;
			$_SESSION[$this->dataKey][$name] = $value;
		}
	}

	public function flash($name){
		return $this->flashs[$name];
	}

	public function setFlash($name,$value){
		if(is_array($name)){
			foreach($name as $key => $data){
				$this->setFlash($key,$data);
			}
		}else{
			$this->flashs[$name] = $value;
			$_SESSION[$this->flashKey][$name] = $value;
		}
	}

	public function keepFlash($name){
		if(is_array($name)){
			foreach($name as $data){
				$this->keepFlash($data);
			}
		}else{
			$this->setFlash($name,$this->flash($name));
		}
	}

}*/

?>