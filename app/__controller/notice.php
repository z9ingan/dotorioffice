<?
if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoricodeController.php"; // parent load
class notice extends dotoricodeController{

	public function __construct(){

		parent::__construct();

	}

	public function index(){

		// 검색어
		if(isset($_GET['search_word']) && $_GET['search_word']){
			$search_word = $_GET['search_word'];
		}

		// 조건 초기화
		$conditions = array();
		$conditions['company_idx'] = $this->mycompany['idx'];

		if(isset($search_word)){
			$conditions[] = " ( ".$this->userModel->getAlias().".user_name LIKE '%".$search_word."%' OR ".$this->userModel->getAlias().".user_id LIKE '%".$search_word."%' OR ".$this->noticeModel->getAlias().".notice_title LIKE '%".$search_word."%' OR ".$this->noticeModel->getAlias().".notice_memo LIKE '%".$search_word."%') ";
		}

		$totals = $this->noticeModel->gets($conditions);
		$total = count($totals);
		$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 1;
		$page_limit = 20; // 한페이지에 출력될 목록 수
		$page_group = 10; // 보여질 페이지 수 ([1][2][3]이런거)
		$page_select = ($page - 1) * $page_limit;
		$page_total = ceil($total / $page_limit); // 총 페이지 수 구하기
		$page_half = floor($page_group / 2);
		$page_offset = $page_limit * $page - $page_limit;
		$this->clean['page'] = $page;
		$this->clean['total'] = $total;
		$this->clean['page_total'] = $page_total;
		$this->clean['page_limit'] = $page_limit;
		$this->clean['page_half'] = $page_half;

		// 공지사항 불러오기
		$notices = $this->noticeModel->gets(
			$conditions,
			null,
			array('idx' => 'DESC'),
			$page_limit,
			$page_offset
		);
		$this->clean['notices'] = $notices;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('notice_index.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function view($notice_idx){

		// 공지사항 불러오기
		$notice = $this->noticeModel->get($notice_idx);
		if(!$notice) $this->error('공지사항 정보를 찾을 수 없습니다.');
		if($notice['company_idx'] != $this->mycompany['idx']) $this->error('올바르지 않습니다.');

		// 첨부파일
		$notice['noticefiles'] = $this->noticefileModel->gets(array('notice_idx' => $notice['idx']));
		$this->clean['notice'] = $notice;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('notice_view.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function add(){

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('notice_form.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function edit($notice_idx){

		$notice = $this->noticeModel->get($notice_idx);
		if(!$notice) $this->error('공지사항이 없습니다.');
		if($notice['company_idx'] != $this->mycompany['idx']) $this->error('올바르지 않습니다.');
		if($this->accessor['user_level'] < 2 && $notice['user_idx'] != $this->accessor['idx']){
			$this->error('관리자 또는 작성자 본인만 수정할 수 있습니다.');
		}

		$this->clean['notice'] = $notice;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('notice_form.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function ajax_add_notice(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 공지사항 제목
			if(!$_POST['notice_title']) throw new Exception('제목이 없습니다.');
			// 공지사항 내용
			if(!$_POST['notice_memo']) throw new Exception('내용이 없습니다.');

			$notice_idx = $this->noticeModel->add(
				array(
					'company_idx' => $this->mycompany['idx'],
					'user_idx' => $this->accessor['idx'],
					'notice_title' => $_POST['notice_title'],
					'notice_memo' => $_POST['notice_memo']
				)
			);
			if(!$notice_idx) throw new Exception('공지사항 추가에 실패하였습니다.');
			
			// 프로젝트파일 저장
			if($_FILES['notice_file']['size'][0] > 0){
				$notice_files = $this->helper->re_array_files($_FILES['notice_file']);
				foreach($notice_files as $ntf){
					// 데이터 저장
					$noticefile_idx = $this->noticefileModel->add(array('notice_idx' => $notice_idx));
					if(!$noticefile_idx) throw new Exception('파일 DB 입력 실패');
					// 파일 처리
					$result = $this->noticefileModel->addFile($noticefile_idx, $ntf);
					if(!$result) throw new Exception('파일 저장에 실패하였습니다.');
				}
			}

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $notice_idx));
		exit;
	}

	public function ajax_edit_notice(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);
			
			// 공지사항 제목
			if(!$_POST['notice_title']) throw new Exception('제목이 없습니다.');
			// 공지사항 내용
			if(!$_POST['notice_memo']) throw new Exception('내용이 없습니다.');

			// 공지사항 불러오기
			$notice = $this->noticeModel->get($_POST['notice_idx']);
			if(!$notice) throw new Exception('공지사항이 없습니다.');
			if($notice['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');
			if($this->accessor['user_level'] < 2 && $notice['user_idx'] != $this->accessor['idx']){
				throw new Exception('관리자 또는 작성자 본인만 수정할 수 있습니다.');
			}

			$result = $this->noticeModel->edit(
				$notice['idx'],
				array(
					'notice_title' => $_POST['notice_title'],
					'notice_memo' => $_POST['notice_memo']
				)
			);
			if(!$result) throw new Exception('공지사항 수정에 실패하였습니다.');
			
			// 프로젝트파일 저장
			if($_FILES['notice_file']['size'][0] > 0){
				$notice_files = re_array_files($_FILES['notice_file']);
				foreach($notice_files as $ntf){
					// 데이터 저장
					$noticefile_idx = $this->noticefileModel->add(array('notice_idx' => $notice['idx']));
					if(!$noticefile_idx) throw new Exception('파일 DB 입력 실패');
					// 파일 처리
					$result = $this->noticefileModel->addFile($noticefile_idx, $ntf);
					if(!$result) throw new Exception('파일 저장에 실패하였습니다.');
				}
			}			

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true'));
		exit;
	}

	public function ajax_delete_notices(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 처리용 idx
			$process_notice_idx = array();
			// 처리용에 담기
			if(is_array($_POST['notice_idx'])) $process_notice_idx = $_POST['notice_idx'];
			else $process_notice_idx[] = $_POST['notice_idx'];

			foreach($process_notice_idx as $notice_idx){

				// 공지사항 불러오기
				$notice = $this->noticeModel->get($notice_idx);
				if(!$notice) throw new Exception('공지사항이 없습니다.');
				if($notice['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');
				if($this->accessor['user_level'] < 2 && $notice['user_idx'] != $this->accessor['idx']){
					throw new Exception('관리자 또는 작성자 본인만 수정할 수 있습니다.');
				}

				$result = $this->noticeModel->delete($notice['idx']);
				if(!$result) throw new Exception('공지사항 삭제에 실패하였습니다.');

				$noticefiles = $this->noticefileModel->gets(array('notice_idx' => $notice['idx']));
				if($noticefiles){
					foreach($noticefiles as $ntf){
						$this->noticefileModel->delete($ntf['idx']);
					}
				}
			}			

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true'));
		exit;
	}

}

?>