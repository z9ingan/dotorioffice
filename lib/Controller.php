<?

abstract class Controller{

	private static $instance;

	public $db;
	public $helper;

	public $session;
	public $load;

	public $action;
	public $params = array();

	public function __construct(){

		self::$instance = $this;

		$this->session = new Session('dotori');
		$this->load = new Loader();
		$this->load->database();
		$this->load->helper();

	}

	public static function getInstance(){

		if(!isset(self::$instance)){

			exit('Config instance isn\'t created');

		}

		return self::$instance;

	}

	protected function file_thumb($file){
		if(!file_exists($file['thumb'])) die('no file');
		header('Content-Type: '.$file['filetype'].'; charset=UTF-8');
		//header('Content-Length: '.$file['filesize']);
		header('Content-Disposition: inline;');
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');
		$fp = fopen($file['thumb'],'rb');
		fpassthru($fp);
		fclose($fp);
	}

	protected function file_image($file){
		if(!file_exists($file['file'])) die('no file');
		header('Content-Type: '.$file['filetype'].'; charset=UTF-8');
		//header('Content-Length: '.$file['filesize']);
		header('Content-Disposition: inline;');
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');
		$fp = fopen($file['file'],'rb');
		fpassthru($fp);
		fclose($fp);
	}

	protected function file_download($file){
		if(!file_exists($file['file'])) die('no file');
		header('Content-Type: '.$file['filetype'].'; charset=UTF-8');
		header('Content-Length: '.$file['filesize']);
		if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")){
			$file['filename'] = urlencode($file['filename']);
		}
		header('Content-Disposition: attachment; filename='.$file['filename']);
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');
		$fp = fopen($file['file'],'rb');
		fpassthru($fp);
		fclose($fp);
		exit;
	}
}

?>