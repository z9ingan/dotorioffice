<?
if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoricodeController.php"; // parent load
class approval extends dotoricodeController{

	public function __construct(){

		parent::__construct();

	}

	public function index(){
		$this->load->view('html_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/index.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function doc($edoc_idx = NULL){
		if($edoc_idx){
			$edoc = $this->edocModel->get($edoc_idx);
			// 문서양식이 없으면 문서양식 선택 페이지
			if(!$edoc || $edoc['company_idx'] != $this->mycompany['idx']) $this->doc_select();
			// 있으면 결재문서 작성 페이지
			else $this->doc_write($edoc_idx);
		}
		else $this->doc_select();
	}

	private function doc_select(){
		$edoc_categorys = $this->edocModel->gets(
			array('company_idx' => $this->mycompany['idx']),
			array('edoc_category')
		);
		foreach($edoc_categorys as &$ed) $ed = $ed['edoc_category'];
		$edocs = $this->edocModel->gets(array('company_idx' => $this->mycompany['idx']));
		foreach($edocs as &$ed){
			$ed['eforms'] = $this->eformModel->gets(array('edoc_idx' => $ed['idx']), null, array('time' => 'DESC'));
		}

		$this->clean['edoc_categorys'] = $edoc_categorys;
		$this->clean['edocs'] = $edocs;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/doc_select.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	private function doc_write($edoc_idx){

		$edoc = $this->edocModel->get($edoc_idx);
		$users = $this->userModel->gets(array('company_idx' => $this->mycompany['idx']));
		
		$this->clean['edoc'] = $edoc;
		$this->clean['users'] = $users;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/doc_write.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function form($eform_idx = NULL){
		if($eform_idx){
			$eform = $this->eformModel->get($eform_idx);
			// 문서양식이 없으면 문서양식 추가 페이지
			if(!$eform || $eform['company_idx'] != $this->mycompany['idx']) $this->form_add();
			// 있으면 문서양식 수정페이지
			else $this->form_edit($eform_idx);
		}
		else $this->form_add();
	}

	private function form_add(){
		$edoc_categorys = $this->edocModel->gets(
			array('company_idx' => $this->mycompany['idx']),
			array('edoc_category')
		);
		foreach($edoc_categorys as &$ed) $ed = $ed['edoc_category'];

		$this->clean['edoc_categorys'] = $edoc_categorys;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/form.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	private function form_edit($eform_idx){
		$eform = $this->eformModel->get($eform_idx);
		$edoc_categorys = $this->edocModel->gets(
			array('company_idx' => $this->mycompany['idx']),
			array('edoc_category')
		);
		foreach($edoc_categorys as &$ed) $ed = $ed['edoc_category'];

		$this->clean['eform'] = $eform;
		$this->clean['edoc_categorys'] = $edoc_categorys;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/form.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function ajax_add_eform(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 관리자 권한 검사
			if($this->accessor['user_level'] != 2){
				throw new Exception('권한이 없습니다.');
			}

			if(!$_POST['edoc_category']) throw new Exception('문서양식 종류가 없습니다.');
			if(!$_POST['edoc_name']) throw new Exception('문서양식 이름이 없습니다.');

			$eform = null;
			$edoc = null;

			// EFORM_IDX 체크
			if(isset($_POST['eform_idx'])){
				$eform = $this->eformModel->get($_POST['eform_idx']);
				if(!$eform) throw new Exception('문서양식을 찾을 수 없습니다. (E-FORM)');
				if($eform['company_idx'] != $this->mycompany['idx']){
					throw new Exception('올바르지 않습니다.');
				}
				$edoc = $this->edocModel->get($eform['edoc_idx']);
				if(!$edoc) throw new Exception('문서양식을 찾을 수 없습니다. (E-DOC)');
			}
			
			// 수정모드
			if($eform && $edoc){
				// eform 만들기
				$eform_idx = $this->eformModel->insert(
					array(
						'company_idx' => $this->mycompany['idx'],
						'user_idx' => $this->accessor['idx'],
						'edoc_idx' => $edoc['idx'],
						'eform_html' => htmlentities($_POST['eform_html']),
						'eform_image' => $_POST['eform_image']
					)
				);
				if(!$eform_idx) throw new Exception('문서양식 저장 실패 (E-FORM)');
				// edoc 수정하기
				if($edoc){
					$result = $this->edocModel->update(
						$edoc['idx'],
						array(
							'eform_idx' => $eform_idx,
							'edoc_category' => $_POST['edoc_category'],
							'edoc_name' => $_POST['edoc_name'],
							'edoc_memo' => $_POST['edoc_memo']
						)
					);
					if(!$result) throw new Exception('문서양식 저장 실패 (E-DOC)');
				}
			}
			// 신규 추가모드
			else{
				// edoc 만들기
				$edoc_idx = $this->edocModel->insert(
					array(
						'company_idx' => $this->mycompany['idx'],
						'user_idx' => $this->accessor['idx'],
						'edoc_category' => $_POST['edoc_category'],
						'edoc_name' => $_POST['edoc_name'],
						'edoc_memo' => $_POST['edoc_memo'],
						'edoc_use' => 1
					)
				);
				if(!$edoc_idx) throw new Exception('문서양식 추가 실패 (E-DOC)');
				// eform 만들기
				$eform_idx = $this->eformModel->insert(
					array(
						'company_idx' => $this->mycompany['idx'],
						'user_idx' => $this->accessor['idx'],
						'edoc_idx' => $edoc_idx,
						'eform_html' => htmlentities($_POST['eform_html']),
						'eform_image' => $_POST['eform_image']
					)
				);
				if(!$eform_idx) throw new Exception('문서양식 추가 실패 (E-FORM)');
				// edoc 업데이트
				$result = $this->edocModel->update($edoc_idx, array('eform_idx' => $eform_idx));
			}

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => false, 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => true, 'eform_idx' => $eform_idx));
		exit;
	}
}
?>