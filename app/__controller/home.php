<?
if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoricodeController.php"; // parent load
class home extends dotoricodeController{

	public function __construct(){

		parent::__construct();

	}

	public function index(){
		$this->load->view('html_header.php', $this->clean);
		$this->load->view('home_index.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function index2(){

		// 공지사항
		$notices = $this->noticeModel->gets(
			array('company_idx' => $this->mycompany['idx']),
			NULL,
			array('idx' => 'DESC'),
			10);
		$this->clean['notices'] = $notices;

		// 오늘의 재고
		$start = mktime(0,0,0,date('n'),date('j'),date('Y'));
		$end = time();
		$this->clean['start'] = $start;
		$this->clean['end'] = $end;

		// 입고항목 불러오기
		$products = $this->productModel->gets(array('company_idx' => $this->mycompany['idx']));

		$pos = $this->poModel->getSumPo($start, $end);

		$inputs = $this->inputModel->getSumInput($start, $end);
		$before_inputs = $this->inputModel->getSumInput(1, $start);

		$outputs = $this->outputModel->getSumOutput($start, $end);
		$before_outputs = $this->outputModel->getSumOutput(1, $start);

		$defects = $this->defectModel->getSumDefect($start, $end);
		$before_defects = $this->defectModel->getSumDefect(1, $start);

		$invens = array();

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('home_index.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function myconfig(){

		$preelines = $this->preelineModel->gets(
			array(
				'company_idx' => $this->mycompany['idx'],
				'user_idx' => $this->accessor['idx']
			),null,array('idx' => 'ASC')
		);

		$prerefers = $this->prereferModel->gets(
			array(
				'company_idx' => $this->mycompany['idx'],
				'user_idx' => $this->accessor['idx']
			),null,array('idx' => 'ASC')
		);

		$users = $this->userModel->gets(
			array(
				'company_idx' => $this->mycompany['idx'],
				'connection_idx' => false,
				'user_level' => array('>=' => 1)
			)
		);

		$sign = $this->signModel->gets(array('user_idx' => $this->accessor['idx']),NULL,array('idx' => 'DESC'),1);

		$this->clean['preelines'] = $preelines;
		$this->clean['prerefers'] = $prerefers;
		$this->clean['users'] = $users;
		$this->clean['sign'] = $sign ? $sign[0] : false;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('approval_myconfig.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function form($edoc_idx = NULL){

		if($edoc_idx){
			$this->editForm($edoc_idx);
		}else{
			$this->addForm();
		}

	}

	private function addForm(){

		$edoc_categorys = $this->edocModel->gets(array('company_idx' => $this->mycompany['idx']),array('edoc_category'));

		$this->clean['edoc_categorys'] = $edoc_categorys;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('approval_form.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	private function editForm($edoc_idx){

		$edoc = $this->edocModel->get($edoc_idx);

		$edoc_categorys = $this->edocModel->gets(array('company_idx' => $this->mycompany['idx']),array('edoc_category'));

		$this->clean['edoc'] = $edoc;
		$this->clean['edoc_categorys'] = $edoc_categorys;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('approval_form.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function doc($edoc_idx = NULL){

		if($edoc_idx){
			$this->writeDoc($edoc_idx);
		}else{
			$this->selectDoc();
		}

	}

	private function writeDoc($edoc_idx){

		$edoc = $this->edocModel->get($edoc_idx);
		if(!$edoc || $edoc['company_idx'] != $this->mycompany['idx']) $this->error('결재양식을 찾을 수 없습니다.');

		$this->clean['edoc'] = $edoc;

		// 미리 불러오기
		$preelines = $this->preelineModel->gets(array('user_idx' => $this->accessor['idx']));
		$prerefers = $this->prereferModel->gets(array('user_idx' => $this->accessor['idx']));
		$this->clean['preelines'] = $preelines;
		$this->clean['prerefers'] = $prerefers;
		
		// 가장 마지막의 전자결재 구하기 (문서번호용)
		$last_approval = $this->approvalModel->gets(array('company_idx' => $this->mycompany['idx']),NULL,array('idx' => 'DESC'),1);

		$this->clean['lastidx'] = $last_approval ? $last_approval[0]['idx'] : 1;

		$users = $this->userModel->gets(array('company_idx' => $this->mycompany['idx'], 'connection_idx' => false));
		$this->clean['users'] = $users;

		$this->load->view('html_header.php', $this->clean);
		if($this->mycompany['idx'] == 1){
			$this->load->view('approval_write_doc_for_demo.php', $this->clean);
		}else{
			$this->load->view('approval_write_doc.php', $this->clean);
		}
		$this->load->view('html_footer.php', $this->clean);
	}

	private function selectDoc(){

		$edoc_categorys = $this->edocModel->gets(array('company_idx' => $this->mycompany['idx']),array('edoc_category'));
		foreach($edoc_categorys as &$ed) $ed = $ed['edoc_category'];
		$edocs = $this->edocModel->gets(array('company_idx' => $this->mycompany['idx']));

		$this->clean['edoc_categorys'] = $edoc_categorys;
		$this->clean['edocs'] = $edocs;

		$this->load->view('html_header.php', $this->clean);
		if($this->mycompany['idx'] == 1){
			$this->load->view('approval_select_doc_for_demo.php', $this->clean);
		}else{
			$this->load->view('approval_select_doc.php', $this->clean);
		}
		$this->load->view('html_footer.php', $this->clean);
	}

	public function archive($list_type = 'incomplete'){

		// 검색조건
		$conditions = array();
		if($list_type == 'temp'){
			$conditions = array(
				'user_idx' => $this->accessor['idx'],
				'approval_status' => '임시'
			);
		}else if($list_type == 'report'){
			$conditions = array(
				'user_idx' => $this->accessor['idx'],
				'approval_status' => array('!=' => '임시')
			);
		}else if($list_type == 'sign'){
			$conditions = array(
				'approval_status' => array('!=' => '임시'),
				$this->approvalModel->getAlias().".idx IN (SELECT approval_idx FROM ".$this->elineModel->getTable()." WHERE eline_user_idx = '".$this->accessor['idx']."')"
			);
		}else if($list_type == 'refer'){
			$conditions = array(
				'approval_status' => array('!=' => '임시'),
				$this->approvalModel->getAlias().".idx IN (SELECT approval_idx FROM ".$this->referModel->getTable()." WHERE refer_user_idx = '".$this->accessor['idx']."')"
			);
		}else if($list_type == 'incomplete'){
			$conditions = array(
				'approval_status' => '진행중',
				'now_user_idx' => $this->accessor['idx']
			);
		}else if($list_type == 'ongoing'){
			$conditions = array(
				'approval_status' => '진행중',
				"(".$this->approvalModel->getAlias().".idx IN (SELECT approval_idx FROM ".$this->elineModel->getTable()." WHERE eline_user_idx = '".$this->accessor['idx']."') OR ".$this->approvalModel->getAlias().".idx IN (SELECT approval_idx FROM ".$this->referModel->getTable()." WHERE refer_user_idx = '".$this->accessor['idx']."'))"
			);
		}else if($list_type == 'complete'){
			$conditions = array(
				'approval_status' => '완료',
				"(".$this->approvalModel->getAlias().".idx IN (SELECT approval_idx FROM ".$this->elineModel->getTable()." WHERE eline_user_idx = '".$this->accessor['idx']."') OR ".$this->approvalModel->getAlias().".idx IN (SELECT approval_idx FROM ".$this->referModel->getTable()." WHERE refer_user_idx = '".$this->accessor['idx']."'))"
			);
		}else if($list_type == 'reject'){
			$conditions = array(
				'approval_status' => '반려',
				"(".$this->approvalModel->getAlias().".idx IN (SELECT approval_idx FROM ".$this->elineModel->getTable()." WHERE eline_user_idx = '".$this->accessor['idx']."') OR ".$this->approvalModel->getAlias().".idx IN (SELECT approval_idx FROM ".$this->referModel->getTable()." WHERE refer_user_idx = '".$this->accessor['idx']."'))"
			);
		}

		$search_word = isset($_GET['search_word']) && $_GET['search_word'] ? $_GET['search_word'] : null;
		$this->clean['search_word'] = $search_word;

		$this->clean['list_type'] = $list_type;

		$search = array();
		if($search_word){
			$query= " user_name LIKE '%".$this->db->escape($search_word)."%' ";
			$query.= " OR approval_no LIKE '%".$this->db->escape($search_word)."%' ";
			$query.= " OR approval_dept LIKE '%".$this->db->escape($search_word)."%' ";
			$query.= " OR approval_title LIKE '%".$this->db->escape($search_word)."%' ";
			$query.= " OR approval_memo LIKE '%".$this->db->escape($search_word)."%' ";
			$search = array($query);
		}

		// 프로젝트 목록
		$page = @$_GET['page'] ? $_GET['page'] : 1;
		$total = @count($this->approvalModel->gets(
			array_merge(array('company_idx' => $this->mycompany['idx']), $conditions, $search)));
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
		
		$approvals = $this->approvalModel->gets(
			array_merge(array('company_idx' => $this->mycompany['idx']), $conditions, $search),
			NULL, // groupby
			array('idx' => 'DESC'),
			$page_limit,
			$page_offset
		);

		foreach($approvals as &$ap){
			$ap['now'] = $ap['now_user_idx'] ? $this->userModel->get($ap['now_user_idx']) : null;
		}

		$this->clean['approvals'] = $approvals;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('approval_archive.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function view($approval_idx = NULL){
		
		if($this->mycompany['idx'] != 1){

			$approval = $this->approvalModel->get($approval_idx);
			if(!$approval || $approval['company_idx'] != $this->mycompany['idx']) throw new Exception('전자결재 정보를 찾을 수 없습니다.');

			// 임시면 수정모드로 보내자
			if($approval['approval_status'] == '임시'){
				$this->edit($approval_idx);
				exit;
			}

			// 권한 검토
			$elines = $this->elineModel->gets(array('approval_idx' => $approval['idx']));
			$refers = $this->referModel->gets(array('approval_idx' => $approval['idx']));

			$eline_users = array();
			$refer_users = array();

			if($elines) foreach($elines as $el) $eline_users[] = $el['eline_user_idx'];
			if($refers) foreach($refers as $rf) $refer_users[] = $rf['refer_user_idx'];
			
			if($approval['user_idx'] != $this->accessor['idx'] && !in_array($this->accessor['idx'], $eline_users) && !in_array($this->accessor['idx'], $refer_users)) throw new Exception('전자결재 정보를 볼 권한이 없습니다.');
			// 권한 검토 끝

			$approvalfiles = $this->approvalfileModel->gets(array('approval_idx' => $approval['idx']));

			$this->clean['approval'] = $approval;
			$this->clean['elines'] = $elines;
			$this->clean['refers'] = $refers;
			$this->clean['approvalfiles'] = $approvalfiles;

		}
		
		$this->load->view('html_header.php', $this->clean);
		if($this->mycompany['idx'] == 1){
			$this->load->view('approval_view_for_demo.php', $this->clean);
		}else{
			$this->load->view('approval_view.php', $this->clean);
		}
		$this->load->view('html_footer.php', $this->clean);
	}

	public function edit($approval_idx){

		$approval = $this->approvalModel->get($approval_idx);
		if(!$approval || $approval['user_idx'] != $this->accessor['idx']) throw new Exception('전자결재 정보를 찾을 수 없습니다.');

		if($approval['approval_status'] != '임시'){ // || ($approval['approval_status'] == '진행중' && $approval['before_user_idx'] == null) == false)
			throw new Exception('수정할 수 없습니다.');
		}

		// 권한 검토
		$elines = $this->elineModel->gets(array('approval_idx' => $approval['idx']));
		$refers = $this->referModel->gets(array('approval_idx' => $approval['idx']));

		$eline_users = array();
		$refer_users = array();

		$approvalfiles = $this->approvalfileModel->gets(array('approval_idx' => $approval['idx']));

		$users = $this->userModel->gets(array('company_idx' => $this->mycompany['idx'], 'connection_idx' => false));
		$this->clean['users'] = $users;

		$this->clean['approval'] = $approval;
		$this->clean['elines'] = $elines;
		$this->clean['refers'] = $refers;
		$this->clean['approvalfiles'] = $approvalfiles;
		
		$this->load->view('html_header.php', $this->clean);
		$this->load->view('approval_edit.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function print_approval($approval_idx){

		$approval = $this->approvalModel->get($approval_idx);
		if(!$approval || $approval['company_idx'] != $this->mycompany['idx']) throw new Exception('전자결재 정보를 찾을 수 없습니다.');

		// 권한 검토
		$elines = $this->elineModel->gets(array('approval_idx' => $approval['idx']));
		$refers = $this->referModel->gets(array('approval_idx' => $approval['idx']));

		$eline_users = array();
		$refer_users = array();

		if($elines) foreach($elines as $el) $eline_users[] = $el['eline_user_idx'];
		if($refers) foreach($refers as $rf) $refer_users[] = $rf['refer_user_idx'];
		
		if($approval['user_idx'] != $this->accessor['idx'] && !in_array($this->accessor['idx'], $eline_users) && !in_array($this->accessor['idx'], $refer_users)) throw new Exception('전자결재 정보를 볼 권한이 없습니다.');
		// 권한 검토 끝

		$approvalfiles = $this->approvalfileModel->gets(array('approval_idx' => $approval['idx']));

		$this->clean['approval'] = $approval;
		$this->clean['elines'] = $elines;
		$this->clean['refers'] = $refers;
		$this->clean['approvalfiles'] = $approvalfiles;
		
		$this->load->view('print_header.php', $this->clean);
		$this->load->view('print_approval.php', $this->clean);
		$this->load->view('print_footer.php', $this->clean);
	}

	public function ajax_add_preeline(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$preeline = $this->preelineModel->get(
				array(
					'user_idx' => $this->accessor['idx'],
					'preeline_user_idx' => $_POST['preeline_user_idx']
				)
			);

			if(!$preeline){
				$preeline_idx = $this->preelineModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'user_idx' => $this->accessor['idx'],
						'preeline_user_idx' => $_POST['preeline_user_idx'],
						'preeline_type' => '결재'
					)
				);
				if(!$preeline_idx) throw new Exception('결재라인 등록에 실패하였습니다.');
			}else{
				throw new Exception('해당 결재자는 이미 결재라인에 포함되어 있습니다.');
			}

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $preeline_idx));
		exit;
	}

	public function ajax_add_prerefer(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$prerefer = $this->prereferModel->get(
				array(
					'user_idx' => $this->accessor['idx'],
					'prerefer_user_idx' => $_POST['prerefer_user_idx']
				)
			);

			if(!$prerefer){
				$prerefer_idx = $this->prereferModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'user_idx' => $this->accessor['idx'],
						'prerefer_user_idx' => $_POST['prerefer_user_idx']
					)
				);
				if(!$prerefer_idx) throw new Exception('참조자 등록에 실패하였습니다.');
			}else{
				throw new Exception('해당 참조자는 이미 결재라인에 포함되어 있습니다.');
			}

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $prerefer_idx));
		exit;
	}

	public function ajax_add_sign(){

		// 데모 차단
		if($this->mycompany['idx'] == 1) exit;

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$clean = array();

			$sign_idx = $this->signModel->add(
				array(
					'company_idx' => $this->mycompany['idx'],
					'user_idx' => $this->accessor['idx']
				)
			);
			if(!$sign_idx) throw new Exception('파일 DB 저장 실패');

			// 프로젝트파일 저장
			if($_FILES['sign_file']['size'] > 0){
				// 파일 처리
				$result = $this->signModel->addFile($sign_idx, $_FILES['sign_file'], 'fit');
				if(!$result) throw new Exception('파일 저장에 실패하였습니다.');
			}

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $sign_idx));
		exit;
	}
	
	public function ajax_rotate_sign_image(){

		// 데모 차단
		if($this->mycompany['idx'] == 1) exit;

		try{

			$sign = $this->signModel->get($_POST['sign_idx']);
			if(!$sign['file']) throw new Exception('서명 이미지가 없습니다.');
			$result = $this->helper->rotateImage($sign['file'], $sign['filetype']);
			if(!$result) throw new Exception('서명 이미지 회전에 실패하였습니다.');
			$result = $this->signModel->addFile($sign['idx'], array('tmp_name' => $sign['file'], 'name' => $sign['filename'], 'size' => $sign['filesize'], 'type' => $sign['filetype']), 'fit');
			if(!$result) throw new Exception('파일 저장에 실패하였습니다.');
		}catch(Exception $e){
			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;
		}
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true'));
		exit;
	}

	public function ajax_delete_preeline(){

		// 데모 차단
		if($this->mycompany['idx'] == 1) exit;

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$preeline = $this->preelineModel->get($_POST['preeline_idx']);
			if(!$preeline || $preeline['user_idx'] != $this->accessor['idx']) throw new Exception('결재자 정보가 올바르지 않습니다.');

			$result = $this->preelineModel->delete($preeline['idx']);
			if(!$result) throw new Exception('실패');

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

	public function ajax_delete_prerefer(){

		// 데모 차단
		if($this->mycompany['idx'] == 1) exit;

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$prerefer = $this->prereferModel->get($_POST['prerefer_idx']);
			if(!$prerefer || $prerefer['user_idx'] != $this->accessor['idx']) throw new Exception('참조자 정보가 올바르지 않습니다.');

			$result = $this->prereferModel->delete($prerefer['idx']);
			if(!$result) throw new Exception('실패');

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

	public function ajax_add_edoc(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$edoc_idx = $this->edocModel->add(
				array(
					'company_idx' => $this->mycompany['idx'],
					'user_idx' => $this->accessor['idx'],
					'edoc_category' => $_POST['edoc_category'],
					'edoc_name' => $_POST['edoc_name'],
					'edoc_description' => $_POST['edoc_description'],
					'edoc_life' => isset($_POST['edoc_life']) ? $_POST['edoc_life'] : 0,
					'edoc_html' => $_POST['edoc_html']
				)
			);
			if(!$edoc_idx) throw new Exception('결재양식 추가에 실패하였습니다.');

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $edoc_idx));
		exit;
	}

	public function ajax_add_approval(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$temp_mode = $_POST['approval_temp'] == 'temp' ? true : false;

			// 가장 마지막의 전자결재 구하기 (문서번호용)
			$last_approval = $this->approvalModel->gets(array('company_idx' => $this->mycompany['idx']),NULL,array('idx' => 'DESC'),1);

			$last_idx = str_pad($last_approval ? $last_approval[0]['idx']+1 : 1, 6, '0', STR_PAD_LEFT);

			$approval_no = $this->mycompany['company_name'].'-'.date('Ym').'-'.$last_idx;

			$approval_idx = $this->approvalModel->add(
				array(
					'company_idx' => $this->mycompany['idx'],
					'user_idx' => $this->accessor['idx'],
					'edoc_idx' => $_POST['edoc_idx'],
					'now_user_idx' => $temp_mode ? null : $_POST['eline_user_idx'][0],
					'approval_no' => $approval_no,
					'approval_dept' => $this->accessor['user_dept'],
					'approval_name' => $this->accessor['user_name'],
					'approval_position' => $this->accessor['user_position'],
					'approval_life' => isset($_POST['approval_life']) ? $_POST['approval_life'] : 0,
					'approval_status' => $temp_mode ? '임시' : '진행중',
					'approval_title' => $_POST['approval_title'],
					'approval_memo' => $_POST['approval_memo']
				)
			);
			if(!$approval_idx) throw new Exception('전자결재 '.($temp_mode ? '임시저장' : '상신').'에 실패하였습니다.');

			// 전자결재파일 저장
			if($_FILES['approval_file']['size'][0] > 0){
				$approval_files = re_array_files($_FILES['approval_file']);
				foreach($approval_files as $apf){
					// 데이터 저장
					$approvalfile_idx = $this->approvalfileModel->add(array('company_idx' => $this->mycompany['idx'], 'approval_idx' => $approval_idx));
					if(!$approvalfile_idx) throw new Exception('파일 DB 입력 실패');
					// 파일 처리
					$result = $this->approvalfileModel->addFile($approvalfile_idx, $apf);
					if(!$result) throw new Exception('파일 저장에 실패하였습니다.');
				}
			}

			for($i=0; $i<count($_POST['eline_user_idx']); $i++){

				$user = $this->userModel->get($_POST['eline_user_idx'][$i]);
				if(!$user) throw new Exception('결재자 오류');

				$eline_idx = $this->elineModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'approval_idx' => $approval_idx,
						'eline_user_idx' => $user['idx'],
						'eline_name' => $user['user_name'],
						'eline_position' => $user['user_position']
					)
				);

				if(!$eline_idx) throw new Exception('결재라인 등록에 실패하였습니다.');
			}

			for($i=0; $i<count($_POST['refer_user_idx']); $i++){

				$user = $this->userModel->get($_POST['refer_user_idx'][$i]);
				if(!$user) throw new Exception('참조자 오류');

				$refer_idx = $this->referModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'approval_idx' => $approval_idx,
						'refer_user_idx' => $user['idx'],
						'refer_type' => 'cc'
					)
				);

				if(!$refer_idx) throw new Exception('참조자 등록에 실패하였습니다.');

			}

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $approval_idx));
		exit;
	}

	public function ajax_edit_approval(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$approval = $this->approvalModel->get($_POST['approval_idx']);
			if(!$approval || $approval['user_idx'] != $this->accessor['idx']) throw new Exception('수정할 수 없습니다.');

			
			$elines = $this->elineModel->gets(array('approval_idx' => $approval['idx']));
			$refers = $this->referModel->gets(array('approval_idx' => $approval['idx']));

			$save_mode = $_POST['submit_type'] == 'save' ? true : false;

			$result = $this->approvalModel->edit($approval['idx'],
				array(
					'time' => time(), // 타임은 새로 바꿔준다.
					'now_user_idx' => $_POST['eline_user_idx'][0],
					'approval_dept' => $this->accessor['user_dept'],
					'approval_name' => $this->accessor['user_name'],
					'approval_position' => $this->accessor['user_position'],
					'approval_life' => isset($_POST['approval_life']) ? $_POST['approval_life'] : 0,
					'approval_status' => $save_mode ? '임시' : '진행중',
					'approval_title' => $_POST['approval_title'],
					'approval_memo' => $_POST['approval_memo']
				)
			);
			if(!$result) throw new Exception('전자결재 '.($save_mode ? '저장' : '상신').'에 실패하였습니다.');

			// 전자결재파일 삭제
			if(isset($_POST['approvalfile_idx'])){
				foreach($_POST['approvalfile_idx'] as $approvalfile_idx){
					$this->approvalfileModel->delete($approvalfile_idx);
				}
			}

			// 전자결재파일 저장
			if($_FILES['approval_file']['size'][0] > 0){
				$approval_files = re_array_files($_FILES['approval_file']);
				foreach($approval_files as $apf){
					// 데이터 저장
					$approvalfile_idx = $this->approvalfileModel->add(array('company_idx' => $this->mycompany['idx'], 'approval_idx' => $approval['idx']));
					if(!$approvalfile_idx) throw new Exception('파일 DB 입력 실패');
					// 파일 처리
					$result = $this->approvalfileModel->addFile($approvalfile_idx, $apf);
					if(!$result) throw new Exception('파일 저장에 실패하였습니다.');
				}
			}

			// 전자결재라인 & 참조 삭제
			if($elines) foreach($elines as $el) $this->elineModel->delete($el['idx']);
			if($refers) foreach($refers as $rf) $this->referModel->delete($rf['idx']);

			for($i=0; $i<count($_POST['eline_user_idx']); $i++){

				$user = $this->userModel->get($_POST['eline_user_idx'][$i]);
				if(!$user) throw new Exception('결재자 오류');

				$eline_idx = $this->elineModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'approval_idx' => $approval['idx'],
						'eline_user_idx' => $user['idx'],
						'eline_name' => $user['user_name'],
						'eline_position' => $user['user_position']
					)
				);

				if(!$eline_idx) throw new Exception('결재라인 등록에 실패하였습니다.');
			}

			for($i=0; $i<count($_POST['refer_user_idx']); $i++){

				$user = $this->userModel->get($_POST['refer_user_idx'][$i]);
				if(!$user) throw new Exception('참조자 오류');

				$refer_idx = $this->referModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'approval_idx' => $approval['idx'],
						'refer_user_idx' => $user['idx'],
						'refer_type' => 'cc'
					)
				);

				if(!$refer_idx) throw new Exception('참조자 등록에 실패하였습니다.');

			}

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $approval['idx']));
		exit;
	}

	public function ajax_sign_approval(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$approval = $this->approvalModel->get($_POST['approval_idx']);
			if(!$approval) throw new Exception('전자결재 정보를 찾을 수 없습니다.');
			if($approval['now_user_idx'] != $this->accessor['idx']) throw new Exception('결재할 수 없습니다.');

			$elines = $this->elineModel->gets(array('approval_idx' => $approval['idx']));
			foreach($elines as $i => $el){
				if($el['eline_user_idx'] == $this->accessor['idx']){
					$sign = $this->signModel->gets(array('user_idx' => $this->accessor['idx']),NULL,array('idx' => 'DESC'),1);
					$result = $this->elineModel->edit($el['idx'], array('eline_check' => time(), 'eline_result' => '결재', 'sign_idx' => $sign ? $sign[0]['idx'] : null));
					if(!$result) throw new Exception('결재정보를 저장하지 못하였습니다.');
					$next_eline = isset($elines[$i+1]) ? $elines[$i+1] : false;
					break;
				}else{
					continue;
				}
			}

			$result = $this->approvalModel->edit($approval['idx'],
				array(
					'now_user_idx' => $next_eline ? $next_eline['eline_user_idx'] : NULL,
					'before_user_idx' => $approval['now_user_idx'] ? $approval['now_user_idx'] : NULL,
					'approval_status' => $next_eline ? '진행중' : '완료'
				)
			);
			if(!$result) throw new Exception('전자결재 상태를 변경하는데 실패하였습니다.');

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

	public function ajax_signall_approval(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$approval = $this->approvalModel->get($_POST['approval_idx']);
			if(!$approval) throw new Exception('전자결재 정보를 찾을 수 없습니다.');
			if($approval['now_user_idx'] != $this->accessor['idx']) throw new Exception('결재할 수 없습니다.');

			$elines = $this->elineModel->gets(array('approval_idx' => $approval['idx']));
			$sign_point = null;
			foreach($elines as $i => $el){
				if($el['eline_user_idx'] == $this->accessor['idx']){
					$sign = $this->signModel->gets(array('user_idx' => $this->accessor['idx']),NULL,array('idx' => 'DESC'),1);
					$result = $this->elineModel->edit($el['idx'], array('eline_check' => time(), 'eline_result' => '결재', 'sign_idx' => $sign ? $sign[0]['idx'] : null));
					if(!$result) throw new Exception('결재정보를 저장하지 못하였습니다.');
					$sign_point = $i;
				}else if($sign_point !== null && $i > $sign_point){
					$result = $this->elineModel->edit($el['idx'], array('eline_result' => '전결'));
					if(!$result) throw new Exception('결재정보를 저장하지 못하였습니다.');
				}
			}

			$result = $this->approvalModel->edit($approval['idx'],
				array(
					'now_user_idx' => null,
					'before_user_idx' => $approval['now_user_idx'] ? $approval['now_user_idx'] : NULL,
					'approval_status' => '완료'
				)
			);
			if(!$result) throw new Exception('전자결재 상태를 변경하는데 실패하였습니다.');

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

	public function ajax_reject_approval(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$approval = $this->approvalModel->get($_POST['approval_idx']);
			if(!$approval) throw new Exception('전자결재 정보를 찾을 수 없습니다.');
			if($approval['now_user_idx'] != $this->accessor['idx']) throw new Exception('결재할 수 없습니다.');

			$elines = $this->elineModel->gets(array('approval_idx' => $approval['idx']));
			foreach($elines as $i => $el){
				if($el['eline_user_idx'] == $this->accessor['idx']){
					$result = $this->elineModel->edit($el['idx'], array('eline_check' => time(), 'eline_result' => '반려'));
					if(!$result) throw new Exception('결재정보를 저장하지 못하였습니다.');
					break;
				}else{
					continue;
				}
			}

			$result = $this->approvalModel->edit($approval['idx'],
				array(
					'now_user_idx' => null,
					'before_user_idx' => $approval['now_user_idx'] ? $approval['now_user_idx'] : NULL,
					'approval_status' => '반려'
				)
			);
			if(!$result) throw new Exception('전자결재 상태를 변경하는데 실패하였습니다.');

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

	public function ajax_cancel_approval(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$approval = $this->approvalModel->get($_POST['approval_idx']);
			if(!$approval) throw new Exception('전자결재 정보를 찾을 수 없습니다.');
			if($approval['before_user_idx'] != $this->accessor['idx']) throw new Exception('결재를 취소할 수 없습니다.');

			$elines = $this->elineModel->gets(array('approval_idx' => $approval['idx']));

			$cancel_point = null;
			foreach($elines as $i => $el){
				if($el['eline_user_idx'] == $approval['before_user_idx']){
					$result = $this->elineModel->edit($el['idx'], array('eline_check' => NULL, 'eline_result' => NULL, 'sign_idx' => NULL));
					if(!$result) throw new Exception('결재정보를 저장하지 못하였습니다.');
					$before_eline = isset($elines[$i-1]) ? $elines[$i-1] : false;
					$cancel_point = $i;
				}else if($cancel_point !== null && $i > $cancel_point){
					$result = $this->elineModel->edit($el['idx'], array('eline_check' => NULL, 'eline_result' => NULL, 'sign_idx' => NULL));
					if(!$result) throw new Exception('결재정보를 저장하지 못하였습니다.');
				}
			}

			$result = $this->approvalModel->edit($approval['idx'],
				array(
					'now_user_idx' => $approval['before_user_idx'] ? $approval['before_user_idx'] : NULL,
					'before_user_idx' => $before_eline ? $before_eline['eline_user_idx'] : NULL,
					'approval_status' => '진행중'
				)
			);
			if(!$result) throw new Exception('전자결재 상태를 변경하는데 실패하였습니다.');

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

	public function ajax_delete_approval(){

		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$approval = $this->approvalModel->get($_POST['approval_idx']);
			if(!$approval) throw new Exception('전자결재 정보를 찾을 수 없습니다.');
			if($approval['user_idx'] != $this->accessor['idx'] || $approval['before_user_idx'] !== null) throw new Exception('결재서류를 삭제할 수 없습니다.');

			$elines = $this->elineModel->gets(array('approval_idx' => $approval['idx']));
			$refers = $this->referModel->gets(array('approval_idx' => $approval['idx']));
			$approvalfiles = $this->approvalfileModel->gets(array('approval_idx' => $approval['idx']));

			if($elines) foreach($elines as $el) $this->elineModel->delete($el['idx']);
			if($refers) foreach($refers as $rf) $this->referModel->delete($rf['idx']);
			if($approvalfiles) foreach($approvalfiles as $apf) $this->approvalfileModel->delete($apf['idx']);

			$result = $this->approvalModel->delete($approval['idx']);
			if(!$result) throw new Exception('결재서류를 삭제하는데 실패하였습니다.');

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