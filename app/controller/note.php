<?

if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoriofficeController.php"; // parent load
class note extends dotoriofficeController{

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->list();
	}

	public function list($category = null){
		
		$this->clean['h2'] = '쪽지';
		$this->clean['breadcrumb'] = array('쪽지 목록' => null);

		// 검색어
		if(isset($_GET['search_word']) && $_GET['search_word']){
			$search_word = $_GET['search_word'];
		}

		// 조건 초기화
		$conditions = array();
		$conditions['company_idx'] = $this->mycompany['idx'];

		if(isset($search_word)){
			$conditions[] = " ( ".$this->noteModel->getAlias().".user_name LIKE '%".$search_word."%' OR ".$this->userModel->getAlias().".user_id LIKE '%".$search_word."%' OR ".$this->userModel->getAlias().".user_position LIKE '%".$search_word."%') ";
		}

		//$totals = $this->noteModel->gets($conditions);
		//$total = count($totals);
		//$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 1;
		//$page_limit = 20; // 한페이지에 출력될 목록 수
		//$page_group = 10; // 보여질 페이지 수 ([1][2][3]이런거)
		//$page_select = ($page - 1) * $page_limit;
		//$page_total = ceil($total / $page_limit); // 총 페이지 수 구하기
		//$page_half = floor($page_group / 2);
		//$page_offset = $page_limit * $page - $page_limit;
		//$this->clean['page'] = $page;
		//$this->clean['total'] = $total;
		//$this->clean['page_total'] = $page_total;
		//$this->clean['page_limit'] = $page_limit;
		//$this->clean['page_half'] = $page_half;
		//
		//// 사용자 불러오기
		//$users = $this->userModel->gets(
		//	$conditions,
		//	null,
		//	array('idx' => 'DESC'),
		//	$page_limit,
		//	$page_offset
		//);
		//
		//$this->clean['users'] = $users;
		//
		//// 전체 사용자
		//$user_total = $this->userModel->gets(array('company_idx' => $this->mycompany['idx']));
		//$user_total = count($user_total);
		//$user_active = $this->userModel->gets(array('company_idx' => $this->mycompany['idx'], 'user_level' => array('!=' => 0)));
		//$user_active = count($user_active);
		//$user_inactive = $this->userModel->gets(array('company_idx' => $this->mycompany['idx'], 'user_level' => 0));
		//$user_inactive = count($user_inactive);
		//$this->clean['user_total'] = $user_total;
		//$this->clean['user_active'] = $user_active;
		//$this->clean['user_inactive'] = $user_inactive;

		$this->load->view('_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/list.php', $this->clean);
		$this->load->view('_footer.php', $this->clean);
	}

	public function note($param1 = NULL){

		if($param1 == 'add'){
			$this->user_add();
		}else if($param1 && ctype_digit($param1)){
			$this->user_edit($param1);
		}else{
			$this->note_list();
		}
	}
}