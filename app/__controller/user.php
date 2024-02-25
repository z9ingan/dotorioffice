<?

class user extends Controller{

	private $accessor = NULL; // Accessor 정보

	private $table_prefix = 'do'; // 테이블 프리픽스

	public function __construct(){

		// Construct
		parent::__construct();

		// User 모델 불러오기
		$this->load->model('userModel');
		$this->userModel->setTable($this->table_prefix.'_user');
		$this->userModel->init(); // Accessor 불러오기

		// Accessor
		$this->accessor = $this->userModel->getAccessor();
		$this->clean['accessor'] = $this->accessor;

	}

	public function login(){
		$this->load->view('login.php');
	}

	public function ajax_logout(){

		$this->session->reset();

		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true'));
		exit;
	}

	public function ajax_login(){
		try{

			$clean = array();
			$clean['company_idx'] = $_POST['company'];
			$clean['user_id'] = $_POST['login_id'];
			$clean['user_password'] = $_POST['login_password'];

			$user = $this->userModel->login($clean['user_id'], $clean['user_password'], $clean['company_idx']);
			if(!$user) throw new Exception('아이디 또는 비밀번호를 확인해주세요.');

			// 이전 페이지 있으면 넘겨주기
			$url = $this->session->data('accessor_back') ? $this->session->data('accessor_back') : BASE_URL;

		}catch(Exception $e){

			header('Content-Type: application/json');
			echo json_encode(array('result' => false, 'message' => $e->getMessage()));
			exit;

		}

		header('Content-Type: application/json');
		echo json_encode(array('result' => true, 'url' => $url));
		exit;
	}

	public function error($message){

		$this->clean['message'] = $message;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('error.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);

		exit; // 종료하기
	
	}

}

?>