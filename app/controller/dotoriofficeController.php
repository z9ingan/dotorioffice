<?

class dotoriofficeController extends Controller{

	protected $accessor = NULL; // Accessor 정보
	protected $mycompany = NULL; // Company 정보
	public $clean; // VIEW CLEAN 데이터

	private $table_prefix = 'do'; // 테이블 프리픽스

	public function __construct(){

		// Construct
		parent::__construct();

		// 헬퍼 View에서도 쓰기
		$this->clean['helper'] = $this->helper;

		// client 모델 불러오기
		$this->load->model('clientModel');
		$this->clientModel->setTable($this->table_prefix.'_client');

		// company 모델 불러오기
		$this->load->model('companyModel');
		$this->companyModel->setTable($this->table_prefix.'_company');

		// dept 모델 불러오기
		$this->load->model('deptModel');
		$this->deptModel->setTable($this->table_prefix.'_dept');

		// user 모델 불러오기
		$this->load->model('userModel');
		$this->userModel->setTable($this->table_prefix.'_user');
		$this->userModel->init(); // Accessor 불러오기

		// postit 모델 불러오기
		$this->load->model('postitModel');
		$this->postitModel->setTable($this->table_prefix.'_postit');

		// note 모델 불러오기
		$this->load->model('noteModel');
		$this->noteModel->setTable($this->table_prefix.'_note');

		// notefile 모델 불러오기
		$this->load->model('notefileModel');
		$this->notefileModel->setTable($this->table_prefix.'_notefile');
		$this->notefileModel->setThumbWidth(400);
		$this->notefileModel->setThumbHeight(300);
		$this->notefileModel->setFileDir(BASE_PATH.'/upload/notefile');
		$this->notefileModel->setThumbDir(BASE_PATH.'/upload/notefile/thumb');

		// folder 모델 불러오기
		$this->load->model('folderModel');
		$this->folderModel->setTable($this->table_prefix.'_folder');

		// board 모델 불러오기
		$this->load->model('boardModel');
		$this->boardModel->setTable($this->table_prefix.'_board');

		// bdaccess 모델 불러오기
		$this->load->model('bdaccessModel');
		$this->bdaccessModel->setTable($this->table_prefix.'_bdaccess');

		// article 모델 불러오기
		$this->load->model('articleModel');
		$this->articleModel->setTable($this->table_prefix.'_article');

		// articlefile 모델 불러오기
		$this->load->model('articlefileModel');
		$this->articlefileModel->setTable($this->table_prefix.'_articlefile');
		$this->articlefileModel->setThumbWidth(400);
		$this->articlefileModel->setThumbHeight(300);
		$this->articlefileModel->setFileDir(BASE_PATH.'/upload/articlefile');
		$this->articlefileModel->setThumbDir(BASE_PATH.'/upload/articlefile/thumb');

		// articletag 모델 불러오기
		$this->load->model('articletagModel');
		$this->articletagModel->setTable($this->table_prefix.'_articletag');

		// articlecomment 모델 불러오기
		$this->load->model('articlecommentModel');
		$this->articlecommentModel->setTable($this->table_prefix.'_articlecomment');

		// edoc 모델 불러오기
		$this->load->model('edocModel');
		$this->edocModel->setTable($this->table_prefix.'_edoc');

		// eform 모델 불러오기
		$this->load->model('eformModel');
		$this->eformModel->setTable($this->table_prefix.'_eform');

		// sign 모델 불러오기
		$this->load->model('signModel');
		$this->signModel->setTable($this->table_prefix.'_sign');

		// approval 모델 불러오기
		$this->load->model('approvalModel');
		$this->approvalModel->setTable($this->table_prefix.'_approval');

		// approvalfile 모델 불러오기
		$this->load->model('approvalfileModel');
		$this->approvalfileModel->setTable($this->table_prefix.'_approvalfile');
		$this->approvalfileModel->setThumbWidth(400);
		$this->approvalfileModel->setThumbHeight(300);
		$this->approvalfileModel->setFileDir(BASE_PATH.'/upload/approvalfile');
		$this->approvalfileModel->setThumbDir(BASE_PATH.'/upload/approvalfile/thumb');

		// apline 모델 불러오기
		$this->load->model('aplineModel');
		$this->aplineModel->setTable($this->table_prefix.'_apline');

		// refer 모델 불러오기
		$this->load->model('referModel');
		$this->referModel->setTable($this->table_prefix.'_refer');

		// Memorize this page
		$this->clean['accessor_back'] = $this->session->data('accessor_back');
		// ajax와 file로 시작하는 메소드는 현재 페이지를 저장하지 않는다.
		if(!preg_match('/^(AJAX_|ajax_|FILE_|file_|PRINT_|print_)/', DOTORI_ACTION)){
			$this->session->setData('accessor_back', THIS_URL);
		}

		// 로그인 여부
		if($this->userModel->isLogged() == TRUE){

			// ACCESSOR Info
			$this->accessor = $this->userModel->getAccessor();
			$this->mycompany = $this->companyModel->get($this->accessor['company_idx']);
			$this->clean['accessor'] = $this->accessor;
			$this->clean['mycompany'] = $this->mycompany;

		}else{
			header('location:'.BASE_URL.'/authentication/login');
			exit;
		}

	}

	public function error($message){

		$this->clean['message'] = $message;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('error.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);

		exit; // 종료하기
	
	}

	public function file_download_noticefile($noticefile_idx){
		$noticefile = $this->noticefileModel->get($noticefile_idx);
		if(!$noticefile) $this->error('파일을 찾을 수 없습니다.');
		$this->file_thumb($noticefile); // 다운로드 실행
	}

	public function file_image_noticefile($noticefile_idx){
		$noticefile = $this->noticefileModel->get($noticefile_idx);
		if(!$noticefile) $this->error('파일을 찾을 수 없습니다.');
		$this->file_image($noticefile); // 이미지 출력 실행
	}

	public function file_thumb_noticefile($noticefile_idx){
		$noticefile = $this->noticefileModel->get($noticefile_idx);
		if(!$noticefile) $this->error('파일을 찾을 수 없습니다.');
		$this->file_thumb($noticefile); // 썸네일 출력 실행
	}

	public function file_thumb_userfile($userfile_idx){
		$userfile = $this->userfileModel->get($userfile_idx);
		if(!$userfile) $this->error('파일을 찾을 수 없습니다.');
		$this->file_thumb($userfile); // 다운로드 실행
	}
}

?>