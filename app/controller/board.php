<?

if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoriofficeController.php"; // parent load
class board extends dotoriofficeController{

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$notice = $this->boardModel->getNotice($this->mycompany['idx']);
		if(!$notice){
			// 데이터 저장
			$notice_idx = $this->boardModel->addBoard(array(
				$this->mycompany['idx'],							// company_idx
				0,													// folder_idx
				1,													// is_notice
				'공지사항',											// board_name
				null,												// board_memo
				1,													// use_board
				1,													// use_header
				1,													// use_file
				1,													// use_board
				1,
				1,
			));
		}
		$this->list($notice['idx']);
	}

	public function list($idx){

		$board = $this->boardModel->get($idx);
		if(!$board) $this->error('올바르지 않습니다.');
		if($board['company_idx'] != $this->company['idx']) $this->error('올바르지 않습니다.');
		
		$this->clean['h2'] = '게시판';
		$this->clean['breadcrumb'] = array('게시판' => null, $board['board_name'] => BASE_URL.'/'.DOTORI_CONTROLLER.'/list/'.$idx);

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

		$this->load->view('_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/list.php', $this->clean);
		$this->load->view('_footer.php', $this->clean);
	}
}