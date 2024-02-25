<?

class Loader{

	public function database(array $db_info = NULL, $name = 'db'){

		if(!$db_info){
			include BASE_PATH.'/'.APP_DIR.'/config.php';
		}

		$DB = new mysqli($db_info['host'], $db_info['id'], $db_info['password'], $db_info['database']);
		if($DB->connect_error){
			die("Connection failed: " . $DB->connect_error);
		}
		$DB->set_charset($db_info['charset']);

		$Controller = Controller::getInstance();
		$Controller->$name = $DB;

	}

	public function helper($name = 'helper'){

		require_once BASE_PATH.'/'.LIB_DIR.'/Helper.php';

		$Helper = new helper();

		$Controller = Controller::getInstance();

		$Controller->$name = $Helper;

	}

	public function model($model_name, $name = NULL){
	
		if(!class_exists($model_name)){

			$model_file = BASE_PATH.'/'.APP_DIR.'/model/'.$model_name.'.php';

			if(!file_exists($model_file)){

				exit('Model file doesn\'t exists /// '.$model_file);

			}

			require_once $model_file;

		}

		$Model = new $model_name();

		$Controller = Controller::getInstance();

		if(is_null($name)){

			$Controller->$model_name = $Model;

		}else{

			$Controller->$name = $Model;

		}

	}

	public function userclass($class_name, array $params = NULL){

		if(!class_exists($class_name)){

			$class_file = BASE_PATH.'/'.APP_DIR.'/userclass/'.$class_name.'.php';

			if(!file_exists($class_file)){

				exit('Class file doesn\'t exists /// '.$class_file);

			}

			require_once $class_file;

		}

		return new $class_name();
	}

	public function view($__viewfile, array $__viewdata = NULL){

		if($__viewdata) extract($__viewdata, EXTR_OVERWRITE);

		include BASE_PATH.'/'.APP_DIR.'/view/'.$__viewfile;

	}

}

?>