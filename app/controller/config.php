<?

if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoriofficeController.php"; // parent load
class config extends dotoriofficeController{

	public function __construct(){
		parent::__construct();
		if($this->accessor['user_level'] < 2) $this->error('권한이 없습니다.');
	}

	public function index(){
		$this->user();
	}

	public function user($param1 = NULL){

		if($param1 == 'add'){
			$this->user_add();
		}else if($param1 && ctype_digit($param1)){
			$this->user_edit($param1);
		}else{
			$this->user_list();
		}
	}

	public function user_list(){

		$this->clean['h2'] = '사용자 관리';
		$this->clean['breadcrumb'] = array('설정' => null);

		// 검색어
		if(isset($_GET['search_word']) && $_GET['search_word']){
			$search_word = $_GET['search_word'];
		}

		// 조건 초기화
		$conditions = array();
		$conditions['company_idx'] = $this->mycompany['idx'];

		if(isset($search_word)){
			$conditions[] = " ( ".$this->userModel->getAlias().".user_name LIKE '%".$search_word."%' OR ".$this->userModel->getAlias().".user_id LIKE '%".$search_word."%' OR ".$this->userModel->getAlias().".user_position LIKE '%".$search_word."%') ";
		}

		$totals = $this->userModel->gets($conditions);
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

		// 사용자 불러오기
		$users = $this->userModel->gets(
			$conditions,
			null,
			array('idx' => 'DESC'),
			$page_limit,
			$page_offset
		);

		$this->clean['users'] = $users;

		// 전체 사용자
		$user_total = $this->userModel->gets(array('company_idx' => $this->mycompany['idx']));
		$user_total = count($user_total);
		$user_active = $this->userModel->gets(array('company_idx' => $this->mycompany['idx'], 'user_level' => array('!=' => 0)));
		$user_active = count($user_active);
		$user_inactive = $this->userModel->gets(array('company_idx' => $this->mycompany['idx'], 'user_level' => 0));
		$user_inactive = count($user_inactive);
		$this->clean['user_total'] = $user_total;
		$this->clean['user_active'] = $user_active;
		$this->clean['user_inactive'] = $user_inactive;

		$this->load->view('_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/user_list.php', $this->clean);
		$this->load->view('_footer.php', $this->clean);
	}

	public function user_add(){
		$this->clean['h2'] = '사용자 추가';
		$this->clean['breadcrumb'] = array('설정' => null, '사용자 관리' => BASE_URL.'/'.DOTORI_CONTROLLER.'/user');

		// 부서 불러오기
		$this->clean['nested_depts'] = $this->deptModel->getDepts($this->mycompany['idx'], 0);
		$this->clean['depts'] = $this->deptModel->gets(array('company_idx' => $this->mycompany['idx']));

		$this->load->view('_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/user_add.php', $this->clean);
		$this->load->view('_footer.php', $this->clean);
	}

	public function user_edit($user_idx){
		$this->clean['h2'] = '사용자 수정';
		$this->clean['breadcrumb'] = array('설정' => null, '사용자 관리' => BASE_URL.'/'.DOTORI_CONTROLLER.'/user');

		// 유저 불러오기
		$user = $this->userModel->get($user_idx);
		if(!$user || $user['company_idx'] != $this->mycompany['idx']) $this->error('올바르지 않은 접근입니다.');
		$this->clean['user'] = $user;

		// 부서 불러오기
		$this->clean['nested_depts'] = $this->deptModel->getDepts($this->mycompany['idx'], 0);
		$this->clean['depts'] = $this->deptModel->gets(array('company_idx' => $this->mycompany['idx']));

		$this->load->view('_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/user_edit.php', $this->clean);
		$this->load->view('_footer.php', $this->clean);
	}

	public function dept(){
		$this->clean['h2'] = '부서 관리';
		$this->clean['breadcrumb'] = array('설정' => null);

		$this->clean['depts'] = $this->deptModel->getDepts($this->mycompany['idx'], 0);
		$this->clean['dept_user_count'] = $this->userModel->getCountDeptUsers($this->mycompany['idx']);

		$this->load->view('_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/dept.php', $this->clean);
		$this->load->view('_footer.php', $this->clean);
	}

	public function ajax_add_child_dept(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 관리자 권한 검사
			if($this->accessor['user_level'] < 2){
				throw new Exception('권한이 없습니다.');
			}

			// 부서 검증
			if($_POST['dept_idx']){
				$dept = $this->deptModel->get($_POST['dept_idx']);
				if(!$dept) throw new Exception('부서 정보를 찾을 수 없습니다.');
				if($dept['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');
				$parent_dept_idx = $dept['idx'];
			}else{
				$parent_dept_idx = 0;
			}

			// 부서명 공백 체크
			if(empty($_POST['dept_name'])){
				throw new Exception('부서명이 올바르지 않습니다.');
			}

			// 부서 순서
			$max = $this->deptModel->getMaxDeptOrder($this->mycompany['idx'], $parent_dept_idx);

			// 데이터 저장
			$dept_idx = $this->deptModel->addDept(array(
				$this->mycompany['idx'],							// company_idx
				$parent_dept_idx,									// parent dept_idx
				$_POST['dept_name'],								// dept_name
				$_POST['dept_memo'] ? $_POST['dept_memo'] : null,	// dept_memo
				($max+1)											// dept_order
			));

			// 결과
			if(!$dept_idx) throw new Exception('부서 추가 실패');

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => false, 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => true, 'dept_idx' => $dept_idx));
		exit;
	}

	public function ajax_edit_dept_name(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 관리자 권한 검사
			if($this->accessor['user_level'] < 2){
				throw new Exception('권한이 없습니다.');
			}

			// 부서 검증
			$dept = $this->deptModel->get($_POST['dept_idx']);
			if(!$dept) throw new Exception('부서 정보를 찾을 수 없습니다.');
			if($dept['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			// 부서명 공백 체크
			if(empty($_POST['dept_name'])){
				throw new Exception('부서명이 올바르지 않습니다.');
			}

			// 데이터 저장
			$result = $this->deptModel->editDeptName($dept['idx'], $_POST['dept_name']);

			// 결과
			if(!$result) throw new Exception('부서명 변경 실패');

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => false, 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => true));
		exit;
	}

	public function ajax_edit_dept_order(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 관리자 권한 검사
			if($this->accessor['user_level'] < 2){
				throw new Exception('권한이 없습니다.');
			}

			// 데이터 처리
			if(!$_POST['tree']){
				throw new Exception('처리할 데이터가 없습니다.');
			}
			$trees = json_decode($_POST['tree'], true);

			// 처리 프로세스 선언
			$process = function($tree, $order) use (&$process){
				$dept = $this->deptModel->get($tree['id']); // 부서 조회
				if(!$dept) throw new Exception('부서 정보를 찾을 수 없습니다.'); // 없으면 에러
				if($dept['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.'); // 자기네 회사 것이 아니면 에러
				$result = $this->deptModel->editDeptOrder($tree['id'], $tree['parent'], ++$order);
				if(!$result) throw new Exception('부서 순서 변경 실패 (id:'.$tree['id'].',parent:'.$tree['parent'].',order:'.$order.')'); // 쿼리 실패

				if($tree['children']){
					foreach($tree['children'] as $i => $child){
						$process($child, $i);
					}
				}
			};

			// 처리
			if($trees){
				foreach($trees as $i => $child){
					$process($child, $i);
				}
			}

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => false, 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => true));
		exit;
	}

	public function ajax_add_user(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 관리자 권한 검사
			if($this->accessor['user_level'] < 2){
				throw new Exception('권한이 없습니다.');
			}

			// 아이디 존재유무 확인
			if($this->userModel->isUserId($_POST['user_id'])){
				throw new Exception('이미 존재하는 아이디 입니다.');
			}
			if(!preg_match('/^[a-z가-힣]+[0-9a-z가-힣]{4,20}$/', $_POST['user_id'])){
				throw new Exception('아이디 생성 규칙이 잘못되었습니다.');
			}

			// 비밀번호 정규표현식
			if(!preg_match('/^.{4,}$/', $_POST['user_password'])){
				throw new Exception('비밀번호는 최소 4자 이상으로 만들어 주세요.');
			}

			// 비밀번호 체크
			if($_POST['user_password'] != $_POST['password_check']){
				throw new Exception('비밀번호가 일치하지 않습니다.');
			}

			// DEPT_IDX 체크
			if($_POST['dept_idx']){ // 값이 있을 때만 체크
				$dept = $this->deptModel->get($_POST['dept_idx']);
				if($dept['company_idx'] != $this->mycompany['idx']){
					throw new Exception('부서가 올바르지 않습니다.');
				}
			}

			// 성별 체크
			if(!in_array($_POST['user_sex'], array('남자', '여자'))){
				throw new Exception('올바르지 않습니다.');
			}

			// 레벨 체크
			if(!in_array($_POST['user_level'], array(0,1,2))){
				throw new Exception('올바르지 않습니다.');
			}

			// 데이터 저장
			$user_idx = $this->userModel->addUser(array(
				$this->mycompany['idx'],												// company_idx
				$_POST['user_id'],														// user_id
				$_POST['user_password'],												// user_password
				$_POST['dept_idx'],														// dept_idx
				$_POST['user_level'],													// user_level
				$_POST['user_name'],													// user_name
				$_POST['user_position'] ? $_POST['user_position'] : null,				// user_position
				$_POST['user_comcode'] ? $_POST['user_comcode'] : null,					// user_comcode
				$_POST['user_regcode'] ? $_POST['user_regcode'] : null,					// user_regcode
				$_POST['user_sex'] ? $_POST['user_sex'] : null,							// user_sex
				$_POST['user_tel'] ? $_POST['user_tel'] : null,							// user_tel
				$_POST['user_direct'] ? $_POST['user_direct'] : null,					// user_direct
				$_POST['user_mobile'] ? $_POST['user_mobile'] : null,					// user_mobile
				$_POST['user_email'] ? $_POST['user_email'] : null,						// user_email
				$_POST['user_zipcode'] ? $_POST['user_zipcode'] : null,					// user_zipcode
				$_POST['user_address1'] ? $_POST['user_address1'] : null,				// user_address1
				$_POST['user_address2'] ? $_POST['user_address2'] : null,				// user_address2
				$_POST['user_x'] ? $_POST['user_x'] : null,								// user_x
				$_POST['user_y'] ? $_POST['user_y'] : null,								// user_y
				$_POST['user_bank'] ? $_POST['user_bank'] : null,						// user_bank
				$_POST['user_bankaccount'] ? $_POST['user_bankaccount'] : null,			// user_bankaccount
				$_POST['user_bankholder'] ? $_POST['user_bankholder'] : null,			// user_bankholder
				$_POST['user_entertime'] ? strtotime($_POST['user_entertime']) : null,	// user_entertime
				$_POST['user_leavetime'] ? strtotime($_POST['user_leavetime']) : null,	// user_leavetime
				$_POST['user_memo'] ? $_POST['user_memo'] : null,						// user_memo
				$_POST['user_photo'] ? $_POST['user_photo'] : null						// user_photo
			));

			// 결과
			if(!$user_idx) throw new Exception('사용자 추가 실패');

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => false, 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => true, 'user_idx' => $user_idx));
		exit;
	}

	public function ajax_edit_user(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 관리자 권한 검사
			if($this->accessor['user_level'] < 2){
				throw new Exception('권한이 없습니다.');
			}

			// 사용자
			$user = $this->userModel->get($_POST['user_idx']);
			if(!$user) throw new Exception('사용자 정보를 찾을 수 없습니다.');
			if($user['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			// 아이디 존재유무 확인
			if($user['user_id'] != $_POST['user_id'] && $this->userModel->isUserId($_POST['user_id'])){
				throw new Exception('이미 존재하는 아이디 입니다.');
			}
			if(!preg_match('/^[a-z가-힣]+[0-9a-z가-힣]{4,20}$/', $_POST['user_id'])){
				throw new Exception('아이디 생성 규칙이 잘못되었습니다.');
			}

			// DEPT_IDX 체크
			if($_POST['dept_idx']){ // 값이 있을 때만 체크
				$dept = $this->deptModel->get($_POST['dept_idx']);
				if($dept['company_idx'] != $this->mycompany['idx']){
					throw new Exception('부서가 올바르지 않습니다.');
				}
			}

			// 성별 체크
			if(!in_array($_POST['user_sex'], array('남자', '여자'))){
				throw new Exception('올바르지 않습니다.');
			}

			// 레벨 체크
			if(!in_array($_POST['user_level'], array(0,1,2))){
				throw new Exception('올바르지 않습니다.');
			}

			// 데이터 저장
			$result = $this->userModel->editUser($user['idx'], array(
				$_POST['user_id'],
				$_POST['dept_idx'],
				$_POST['user_level'],
				$_POST['user_name'],
				$_POST['user_position'] ? $_POST['user_position'] : null,
				$_POST['user_comcode'] ? $_POST['user_comcode'] : null,
				$_POST['user_regcode'] ? $_POST['user_regcode'] : null,
				$_POST['user_sex'] ? $_POST['user_sex'] : null,
				$_POST['user_tel'] ? $_POST['user_tel'] : null,
				$_POST['user_direct'] ? $_POST['user_direct'] : null,
				$_POST['user_mobile'] ? $_POST['user_mobile'] : null,
				$_POST['user_email'] ? $_POST['user_email'] : null,
				$_POST['user_zipcode'] ? $_POST['user_zipcode'] : null,
				$_POST['user_address1'] ? $_POST['user_address1'] : null,
				$_POST['user_address2'] ? $_POST['user_address2'] : null,
				$_POST['user_x'] ? $_POST['user_x'] : null,
				$_POST['user_y'] ? $_POST['user_y'] : null,
				$_POST['user_bank'] ? $_POST['user_bank'] : null,
				$_POST['user_bankaccount'] ? $_POST['user_bankaccount'] : null,
				$_POST['user_bankholder'] ? $_POST['user_bankholder'] : null,
				$_POST['user_entertime'] ? strtotime($_POST['user_entertime']) : null,
				$_POST['user_leavetime'] ? strtotime($_POST['user_leavetime']) : null,
				$_POST['user_memo'] ? $_POST['user_memo'] : null,
				$_POST['user_photo'] ? $_POST['user_photo'] : null
			));

			// 결과
			if(!$result) throw new Exception('사용자 정보 수정 실패');

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => false, 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => true));
		exit;
	}

	public function ajax_edit_user_password(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 관리자 권한 or 본인 검사
			if($this->accessor['user_level'] < 2 && $this->accessor['idx'] != $_POST['user_idx']){
				throw new Exception('권한이 없습니다.');
			}

			// 사용자
			$user = $this->userModel->get($_POST['user_idx']);
			if(!$user) throw new Exception('사용자 정보를 찾을 수 없습니다.');
			if($user['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			// 비밀번호 정규표현식
			if(!preg_match('/^.{4,}$/', $_POST['user_password'])){
				throw new Exception('비밀번호는 최소 4자 이상으로 만들어 주세요.');
			}

			// 비밀번호 체크
			if($_POST['user_password'] != $_POST['password_check']){
				throw new Exception('비밀번호가 일치하지 않습니다.');
			}

			// 데이터 저장
			$result = $this->userModel->editUserPassword($user['idx'], $_POST['user_password']);

			// 결과
			if(!$result) throw new Exception('비밀번호 수정 실패');

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => false, 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => true));
		exit;
	}

}

?>