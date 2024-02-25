<?

/*-------------------------------------------------------------------------------------------------

	dotoricode framework

-------------------------------------------------------------------------------------------------*/

define('APP_DIR', 'app');
define('LIB_DIR', 'lib');

define('ROOT_PATH', str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']));
define('REAL_PATH', str_replace("\\", "/", dirname(__FILE__)));
define('BASE_PATH', str_replace("\\", "/", dirname($_SERVER['SCRIPT_FILENAME'])));
define('BASE_DIR', str_replace(ROOT_PATH,'',BASE_PATH));

// HTTPS 연결 확인
$scheme = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] === 1) || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';

define('SITE_URL', $scheme.$_SERVER['HTTP_HOST']);
define('BASE_URL', SITE_URL.BASE_DIR);
define('VIEW_URL', BASE_URL.'/'.APP_DIR.'/view');
define('THIS_URL', SITE_URL.$_SERVER['REQUEST_URI']);

// 필수 파일 불러오기
require_once BASE_PATH.'/'.LIB_DIR.'/Controller.php';
require_once BASE_PATH.'/'.LIB_DIR.'/Loader.php';
require_once BASE_PATH.'/'.LIB_DIR.'/Model.php';
require_once BASE_PATH.'/'.LIB_DIR.'/Session.php';

// 함수
require_once BASE_PATH.'/'.LIB_DIR.'/functions/str_contains.php';
require_once BASE_PATH.'/'.LIB_DIR.'/functions/str_starts_with.php';
require_once BASE_PATH.'/'.LIB_DIR.'/functions/str_ends_with.php';
require_once BASE_PATH.'/'.LIB_DIR.'/functions/fdiv.php';
require_once BASE_PATH.'/'.LIB_DIR.'/functions/get_debug_type.php';
require_once BASE_PATH.'/'.LIB_DIR.'/functions/get_resource_id.php';
require_once BASE_PATH.'/'.LIB_DIR.'/functions/get_resource_type.php';
require_once BASE_PATH.'/'.LIB_DIR.'/functions/array_key_last.php';

// 시간대 지정
date_default_timezone_set('Asia/Seoul');

// URI 처리
$uri_requests = filter_var($_GET['dotori'], FILTER_SANITIZE_URL);
$divided = explode('/', $uri_requests);

// 컨트롤러와 액션 지정
$ctrl_name = isset($divided[0]) ? $divided[0] : 'home';
$action_name = (count($divided) > 1) ? $divided[1] : 'index';
$params = array_slice($divided, 2);
$ctrl_file = BASE_PATH.'/'.APP_DIR.'/controller/'.$ctrl_name.'.php';

define('DOTORI_CONTROLLER', $ctrl_name);
define('DOTORI_ACTION', $action_name);

try{

	if(!file_exists($ctrl_file)){

		throw new Exception('Controller not found');

	}
	
	require_once $ctrl_file;
		
	if(!class_exists($ctrl_name)){

		throw new Exception('Controller Class not found');

	}

	if(!method_exists($ctrl_name, $action_name)){
	
		throw new Exception('Page not found');

	}

	$Controller = new $ctrl_name();
	$Controller->action = $action_name;
	$Controller->params = $params;

	call_user_func_array(array($Controller, $action_name), $params); // 액션 실행

}catch(Exception $e){

	require_once BASE_PATH.'/error.php';

}

?>