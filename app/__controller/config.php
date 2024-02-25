<?
if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoricodeController.php"; // parent load
class config extends dotoricodeController{

	public function __construct(){

		parent::__construct();

		if($this->accessor['user_level'] < 2) $this->error('권한이 없습니다.');

	}

	public function user($param1 = NULL){

		if($param1 && $param1 == 'add'){
			$this->user_add();
		}else if($param1 && ctype_digit($param1)){
			$this->user_edit($param1);
		}else{
			$this->user_list();
		}
	}

	public function user_add(){

		$depts = $this->deptModel->gets(array('company_idx' => $this->mycompany['idx']));

		$this->clean['depts'] = $depts;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/user_add.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function user_edit($user_idx){

		$user = $this->userModel->get($user_idx);
		if(!$user || $user['company_idx'] != $this->mycompany['idx']) $this->error('올바르지 않은 접근입니다.');

		$depts = $this->deptModel->gets(array('company_idx' => $this->mycompany['idx']));

		$this->clean['user'] = $user;
		$this->clean['depts'] = $depts;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/user_edit.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function user_list(){

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

		$this->load->view('html_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/user_list.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	// 조직도 트리구조 (부서, 사용자) 만들기
	private function setDepts(&$dept){
		$dept['depts'] = $this->deptModel->gets(
			array('company_idx' => $dept['company_idx'], 'dept_idx' => $dept['idx']),
			null,
			array('dept_order' => 'ASC')
		);
		$dept['users'] = $this->userModel->gets(
			array('company_idx' => $dept['company_idx'], 'dept_idx' => $dept['idx']),
			null,
			array('user_order' => 'ASC')
		);
		if($dept['depts']){
			foreach($dept['depts'] as &$dp){
				$this->setDepts($dp);
			}
		}
	}

	public function dept(){

		// 조직도
		$org = array('dept_name' => $this->mycompany['company_name'], 'dept_memo' => null, 'idx' => null);

		// 부서 불러오기
		$org['depts'] = $this->deptModel->gets(
			array('company_idx' => $this->mycompany['idx'], 'dept_idx' => false),
			null,
			array('dept_order' => 'ASC')
		);
		
		$org['users'] = $this->userModel->gets(
			array('company_idx' => $this->mycompany['idx'], 'dept_idx' => false),
			null,
			array('user_order' => 'ASC')
		);
		foreach($org['depts'] as &$dp) $this->setDepts($dp);

		$this->clean['org'] = $org;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/dept.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function excel_category(){

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('setting_excel_category.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function excel_product(){

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('setting_excel_product.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function excel_basic(){

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('setting_excel_basic.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function ajax_excel_basic(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			if(!isset($_POST['excel_content']) || !$_POST['excel_content']) throw new Exception('입력된 데이터가 없습니다.');

			// 입력데이터
			$rows = explode("\n", stripslashes($_POST['excel_content']));
			
			// 입고용 데이터 초기화
			$excels = array();

			// 줄
			for($i=0;$i<count($rows);$i++){
				if(!$rows[$i]) continue; // 비어있으면 스킵...
				$row = explode("\t", $rows[$i]);

				$category = $this->categoryModel->get(
					array(
						'company_idx' => $this->mycompany['idx'],
						'category_code' => $row[0]
					)
				);
				if(!$category) throw new Exception('제품코드를 찾을 수 없습니다.');

				$product = $this->productModel->get(
					array(
						'company_idx' => $this->mycompany['idx'],
						'category_idx' => $category['idx'],
						'product_color' => $row[2],
						'product_size' => array('like' => $row[3])
					)
				);
				if($product){
					$result = $this->productModel->edit($product['idx'],array('basic_qty' => $row[4]));
					if(!$result) throw new Exception('기초재고 등록에 실패하였습니다.');
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

	public function ajax_excel_category(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			if(!isset($_POST['excel_content']) || !$_POST['excel_content']) throw new Exception('입력된 데이터가 없습니다.');

			// 입력데이터
			$rows = explode("\n", stripslashes($_POST['excel_content']));
			
			// 입고용 데이터 초기화
			$excels = array();

			// 줄
			for($i=0;$i<count($rows);$i++){
				if(!$rows[$i]) continue; // 비어있으면 스킵...
				$row = explode("\t", $rows[$i]);

				$category_idx = $this->categoryModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'category_code' => $row[0],
						'category_name' => $row[1]
					)
				);
				if(!$category_idx) throw new Exception('제품코드 생성에 실패하였습니다.');
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

	public function ajax_excel_product(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			if(!isset($_POST['excel_content']) || !$_POST['excel_content']) throw new Exception('입력된 데이터가 없습니다.');

			// 입력데이터
			$rows = explode("\n", stripslashes($_POST['excel_content']));
			
			// 입고용 데이터 초기화
			$excels = array();

			// 줄
			for($i=0;$i<count($rows);$i++){
				if(!$rows[$i]) continue; // 비어있으면 스킵...
				$row = explode("\t", $rows[$i]);

				// 카테고리 조회
				$category = $this->categoryModel->get(array('company_idx' => $this->mycompany['idx'], 'category_code' => $row[0]));
				if(!$category) throw new Exception('제품코드를 찾을 수 없습니다.');

				// 품목코드 생성
				$product_idx = $this->productModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'category_idx' => $category['idx'],
						'product_code' => $row[1],
						'product_color' => $row[2],
						'product_size' => $row[3]
					)
				);

				if(!$product_idx) throw new Exception('품목코드 생성에 실패하였습니다.');

				$this->db->commit(); // 성공, 실행
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

	

	public function ajax_add_user(){

		try{

			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 관리자 권한 검사
			if($this->accessor['user_level'] != 2){
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
			if(!in_array($_POST['user_sex'], array('남자', '여자', '기타'))){
				throw new Exception('올바르지 않습니다.');
			}

			// 레벨 체크
			if(!in_array($_POST['user_level'], array(0,1,2))){
				throw new Exception('올바르지 않습니다.');
			}

			// 데이터 저장
			$user_idx = $this->userModel->insert(
				array(
					'company_idx' => $this->mycompany['idx'],
					'user_id' => $_POST['user_id'],
					'user_password' => $_POST['user_password'],
					'dept_idx' => $_POST['dept_idx'],
					'user_level' => $_POST['user_level'],
					'user_name' => $_POST['user_name'],
					'user_position' => $_POST['user_position'] ? $_POST['user_position'] : null,
					'user_comcode' => $_POST['user_comcode'] ? $_POST['user_comcode'] : null,
					'user_regcode' => $_POST['user_regcode'] ? $_POST['user_regcode'] : null,
					'user_sex' => $_POST['user_sex'] ? $_POST['user_sex'] : null,
					'user_tel' => $_POST['user_tel'] ? $_POST['user_tel'] : null,
					'user_direct' => $_POST['user_direct'] ? $_POST['user_direct'] : null,
					'user_mobile' => $_POST['user_mobile'] ? $_POST['user_mobile'] : null,
					'user_email' => $_POST['user_email'] ? $_POST['user_email'] : null,
					'user_zipcode' => $_POST['user_zipcode'] ? $_POST['user_zipcode'] : null,
					'user_address1' => $_POST['user_address1'] ? $_POST['user_address1'] : null,
					'user_address2' => $_POST['user_address2'] ? $_POST['user_address2'] : null,
					'user_x' => $_POST['user_x'] ? $_POST['user_x'] : null,
					'user_y' => $_POST['user_y'] ? $_POST['user_y'] : null,
					'user_bank' => $_POST['user_bank'] ? $_POST['user_bank'] : null,
					'user_bankaccount' => $_POST['user_bankaccount'] ? $_POST['user_bankaccount'] : null,
					'user_bankholder' => $_POST['user_bankholder'] ? $_POST['user_bankholder'] : null,
					'user_entertime' => $_POST['user_entertime'] ? strtotime($_POST['user_entertime']) : null,
					'user_leavetime' => $_POST['user_leavetime'] ? strtotime($_POST['user_leavetime']) : null,
					'user_memo' => $_POST['user_memo'] ? $_POST['user_memo'] : null,
					'user_photo' => $_POST['user_photo'] ? $_POST['user_photo'] : null
				)
			);

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

			// CLEAN 준비
			$clean = array();

			// 사용자
			$user = $this->userModel->get($_POST['idx']);
			if(!$user) throw new Exception('사용자 정보를 찾을 수 없습니다.');
			if($user['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			// 아이디 존재유무 확인
			if($user['user_id'] != $_POST['user_id'] && $this->userModel->isUserId($_POST['user_id'])){
				throw new Exception('이미 존재하는 아이디 입니다.');
			}
			if(!preg_match('/^[a-z가-힣]+[0-9a-z가-힣]{4,20}$/', $_POST['user_id'])){
				throw new Exception('아이디 생성 규칙이 잘못되었습니다.');
			}

			if($_POST['user_password']){

				// 비밀번호 정규표현식
				if(!preg_match('/^.{4,}$/', $_POST['user_password'])){
					throw new Exception('비밀번호는 최소 4자 이상으로 만들어 주세요.');
				}

				// 비밀번호 체크
				if($_POST['user_password'] != $_POST['password_check']){
					throw new Exception('비밀번호가 일치하지 않습니다.');
				}

				$clean['user_password'] = $_POST['user_password'];

			}

			// DEPT_IDX 체크
			if($_POST['dept_idx']){ // 값이 있을 때만 체크
				$dept = $this->deptModel->get($_POST['dept_idx']);
				if($dept['company_idx'] != $this->mycompany['idx']){
					throw new Exception('부서가 올바르지 않습니다.');
				}
			}

			// 성별 체크
			if(!in_array($_POST['user_sex'], array('남자', '여자', '기타'))){
				throw new Exception('올바르지 않습니다.');
			}

			// 레벨 체크
			if(!in_array($_POST['user_level'], array(0,1,2))){
				throw new Exception('올바르지 않습니다.');
			}

			// CLEAN 정리
			$clean['company_idx'] = $this->mycompany['idx'];
			$clean['user_id'] = $_POST['user_id'];
			// $clean['user_password'] = $_POST['user_password']; // 위에서 따로 추가
			$clean['dept_idx'] = $_POST['dept_idx'];
			$clean['user_level'] = $_POST['user_level'];
			$clean['user_name'] = $_POST['user_name'];
			$clean['user_position'] = $_POST['user_position'] ? $_POST['user_position'] : null;
			$clean['user_comcode'] = $_POST['user_comcode'] ? $_POST['user_comcode'] : null;
			$clean['user_regcode'] = $_POST['user_regcode'] ? $_POST['user_regcode'] : null;
			$clean['user_sex'] = $_POST['user_sex'] ? $_POST['user_sex'] : null;
			$clean['user_tel'] = $_POST['user_tel'] ? $_POST['user_tel'] : null;
			$clean['user_direct'] = $_POST['user_direct'] ? $_POST['user_direct'] : null;
			$clean['user_mobile'] = $_POST['user_mobile'] ? $_POST['user_mobile'] : null;
			$clean['user_email'] = $_POST['user_email'] ? $_POST['user_email'] : null;
			$clean['user_zipcode'] = $_POST['user_zipcode'] ? $_POST['user_zipcode'] : null;
			$clean['user_address1'] = $_POST['user_address1'] ? $_POST['user_address1'] : null;
			$clean['user_address2'] = $_POST['user_address2'] ? $_POST['user_address2'] : null;
			$clean['user_x'] = $_POST['user_x'] ? $_POST['user_x'] : null;
			$clean['user_y'] = $_POST['user_y'] ? $_POST['user_y'] : null;
			$clean['user_bank'] = $_POST['user_bank'] ? $_POST['user_bank'] : null;
			$clean['user_bankaccount'] = $_POST['user_bankaccount'] ? $_POST['user_bankaccount'] : null;
			$clean['user_bankholder'] = $_POST['user_bankholder'] ? $_POST['user_bankholder'] : null;
			$clean['user_entertime'] = $_POST['user_entertime'] ? strtotime($_POST['user_entertime']) : null;
			$clean['user_leavetime'] = $_POST['user_leavetime'] ? strtotime($_POST['user_leavetime']) : null;
			$clean['user_memo'] = $_POST['user_memo'] ? $_POST['user_memo'] : null;
			$clean['user_photo'] = $_POST['user_photo'] ? $_POST['user_photo'] : null;

			// 데이터 저장
			$result = $this->userModel->update($user['idx'], $clean);

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

	public function ajax_edit_user_level(){

		try{

			$user = $this->userModel->get($_POST['user_idx']);
			if(!$user) throw new Exception('사용자를 찾지 못하였습니다.');

			if(in_array($_POST['value'], array(0,1))){
				$result = $this->userModel->edit($user['idx'], array('user_level' => $_POST['value']));
				if(!$result) throw new Exception('등급 변경에 실패하였습니다.');
			}else{
				throw new Exception('올바르지 않습니다.');
			}

		}catch(Exception $e){
			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;
		}
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true'));
		exit;
	}

	public function category(){

		$categorys = $this->categoryModel->gets(array('company_idx' => $this->mycompany['idx']));
		$this->clean['categorys'] = $categorys;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('setting_category.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function product(){
		
		// 검색어
		if(isset($_GET['search_word']) && $_GET['search_word']){
			$search_word = $_GET['search_word'];
		}

		// 조건 초기화
		$conditions = array();
		$conditions['company_idx'] = $this->mycompany['idx'];

		if(isset($search_word)){
			$conditions[] = " ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%') ";
		}

		$totals = $this->productModel->gets($conditions);
		$total = count($totals);
		$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 1;
		$page_limit = 50; // 한페이지에 출력될 목록 수
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

		$this->clean['search_word'] = @$search_word;

		$categorys = $this->categoryModel->gets(array('company_idx' => $this->mycompany['idx']));
		$this->clean['categorys'] = $categorys;

		$products = $this->productModel->gets(
			$conditions,
			null,
			array('idx' => 'DESC'),
			$page_limit,
			$page_offset
		);
		$this->clean['products'] = $products;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('setting_product.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function ajax_add_category(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 중복 체크
			$category = $this->categoryModel->get(
				array(
					'company_idx' => $this->mycompany['idx'],
					'category_code' => $_POST['category_code']
				)
			);
			if($category) throw new Exception('동일한 제품코드가 이미 있습니다.');

			$category_idx = $this->categoryModel->add(
				array(
					'company_idx' => $this->mycompany['idx'],
					'category_code' => $_POST['category_code'],
					'category_name' => $_POST['category_name']
				)
			);
			if(!$category_idx) throw new Exception('제품코드 추가에 실패하였습니다.');
			

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $category_idx));
		exit;
	}

	public function ajax_edit_category(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 카테고리 불러오기
			$category = $this->categoryModel->get($_POST['category_idx']);
			if(!$category) throw new Exception('제품코드 정보를 찾을 수 없습니다.');
			if($category['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			// 중복 체크
			$categorys = $this->categoryModel->gets(
				array(
					'idx' => array('!=' => $category['idx']),
					'company_idx' => $this->mycompany['idx'],
					'category_code' => $_POST['category_code']
				)
			);
			if($categorys) throw new Exception('동일한 제품코드가 이미 있습니다.');

			$result = $this->categoryModel->edit(
				$category['idx'],
				array(
					'category_code' => $_POST['category_code'],
					'category_name' => $_POST['category_name']
				)
			);
			if(!$result) throw new Exception('제품코드 수정에 실패하였습니다.');
			

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

	public function ajax_delete_category(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 카테고리 불러오기
			$category = $this->categoryModel->get($_POST['category_idx']);
			if(!$category) throw new Exception('제품코드 정보를 찾을 수 없습니다.');
			if($category['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			$result = $this->categoryModel->delete($category['idx']);
			if(!$result) throw new Exception('제품코드 삭제에 실패하였습니다.');
			

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

	public function ajax_add_product(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 중복 체크
			$product = $this->productModel->get(
				array(
					'company_idx' => $this->mycompany['idx'],
					'product_code' => $_POST['product_code']
				)
			);
			if($product) throw new Exception('동일한 품목코드가 이미 있습니다.');

			// 카테고리 체크
			$category = $this->categoryModel->get($_POST['category_idx']);
			if(!$category) throw new Exception('제품코드가 없습니다.');
			if($category['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			$product_idx = $this->productModel->add(
				array(
					'company_idx' => $this->mycompany['idx'],
					'category_idx' => $_POST['category_idx'],
					'product_code' => $_POST['product_code'],
					//'product_name' => $_POST['product_name'],
					'product_color' => $_POST['product_color'],
					'product_size' => $_POST['product_size'],
					//'product_memo' => $_POST['product_memo']
				)
			);
			if(!$product_idx) throw new Exception('품목코드 추가에 실패하였습니다.');
			

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $product_idx));
		exit;
	}

	public function ajax_edit_product(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 품목코드 불러오기
			$product = $this->productModel->get($_POST['product_idx']);
			if(!$product) throw new Exception('품목코드 정보를 찾을 수 없습니다.');
			if($product['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			// 중복 체크
			$products = $this->productModel->gets(
				array(
					'idx' => array('!=' => $product['idx']),
					'company_idx' => $this->mycompany['idx'],
					'product_code' => $_POST['product_code']
				)
			);
			if($products) throw new Exception('동일한 품목코드가 이미 있습니다.');

			// 카테고리 체크
			$category = $this->categoryModel->get($_POST['category_idx']);
			if(!$category) throw new Exception('제품코드가 없습니다.');
			if($category['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			$result = $this->productModel->edit(
				$product['idx'],
				array(
					'category_idx' => $_POST['category_idx'],
					'product_code' => $_POST['product_code'],
					//'product_name' => $_POST['product_name'],
					'product_color' => $_POST['product_color'],
					'product_size' => $_POST['product_size'],
					//'product_memo' => $_POST['product_memo']
				)
			);
			if(!$result) throw new Exception('품목코드 수정에 실패하였습니다.');
			

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

	public function ajax_delete_product(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 품목코드 불러오기
			$product = $this->productModel->get($_POST['product_idx']);
			if(!$product) throw new Exception('품목코드 정보를 찾을 수 없습니다.');
			if($product['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			$result = $this->productModel->delete($product['idx']);
			if(!$result) throw new Exception('품목코드 삭제에 실패하였습니다.');
			

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

	public function ajax_set_basic_qty(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			$product = $this->productModel->get($_POST['product_idx']);
			if(!$product) throw new Exception('제품 정보를 찾을 수 없습니다.');
			if($product['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			if(!isset($_POST['basic_qty']) || !is_numeric($_POST['basic_qty'])) throw new Exception('재고수량이 올바르지 않습니다.');

			$result = $this->productModel->edit($product['idx'], array('basic_qty' => $_POST['basic_qty']));
			if(!$result) throw new Exception('기초재고 변경에 실패하였습니다.');		

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