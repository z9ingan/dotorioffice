<?
if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoricodeController.php"; // parent load
class defect extends dotoricodeController{

	public function __construct(){

		parent::__construct();

	}

	public function excel(){

		if(!isset($_POST['excel_content']) || !$_POST['excel_content']) $this->error('불량할 데이터가 없습니다.');

		// 입력데이터
		$rows = explode("\n", stripslashes($_POST['excel_content']));
		
		// 불량용 데이터 초기화
		$excels = array();

		// 줄
		for($i=0;$i<count($rows);$i++){
			if(!$rows[$i]) continue; // 비어있으면 스킵...
			$row = explode("\t", $rows[$i]);

			// 제품코드 조회
			$product = $this->productModel->get(array('company_idx' => $this->mycompany['idx'], 'product_code' => $row[26]));
			
			// 담아주기
			$excels[] = array(
				'product' => $product,
				'excel_code' => $row[26],
				'excel_name' => $row[25],
				'excel_qty' => str_replace(",", "", $row[14]),
				'excel_price' => 0,
				'excel_amount' => 0,
				'excel_tax' => 0,
				'excel_tamount' => 0,
				'excel_store' => null,
				'excel_delivery_charge' => $row[17],
				'excel_order_no' => $row[18],
				'excel_customer_name' => $row[20],
				'excel_customer_tel' => $row[21],
				'excel_customer_zipcode' => $row[22],
				'excel_customer_address' => $row[23],
				'excel_customer_message' => null,
				'excel_product_name' => $row[25],
				'excel_options' => $row[27],
			);

			$this->clean['excels'] = $excels;
			$this->clean['defect_time'] = $_POST['defect_time'];
		}

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('defect_excel.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function index($defect_idx = null){

		// 불량항목 불러오기 (수정모드)
		if($defect_idx){
			$defect = $this->defectModel->get($defect_idx);
			$product_colors = $this->productModel->gets(
				array('category_idx' => $defect['category_idx']),
				array('product_color'),
				array('product_color' => 'ASC')
			);
			$product_sizes = $this->productModel->gets(
				array('category_idx' => $defect['category_idx'], 'product_color' => $defect['product_color']),
				null,
				array('product_size' => 'ASC')
			);
			
			$this->clean['defect'] = $defect;
			$this->clean['product_colors'] = $product_colors;
			$this->clean['product_sizes'] = $product_sizes;
		}

		// 카테고리 불러오기
		$categorys = $this->categoryModel->gets(array('company_idx' => $this->mycompany['idx']));
		$this->clean['categorys'] = $categorys;

		// 검색
		// 시작날짜
		if(isset($_GET['start']) && preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_GET['start'])){
			list($year, $month, $date) = explode('-', $_GET['start']);
			$start = mktime(0,0,0,$month, $date, $year);
		}
		// 종료날짜
		if(isset($_GET['end']) && preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_GET['end'])){
			list($year, $month, $date) = explode('-', $_GET['end']);
			$end = mktime(23,59,59,$month, $date, $year);
		}
		// 검색어
		if(isset($_GET['search_word']) && $_GET['search_word']){
			$search_word = $_GET['search_word'];
		}

		// 조건 초기화
		$conditions = array();
		$conditions['company_idx'] = $this->mycompany['idx'];

		// 날짜가 있으면
		if(isset($start, $end)){
			$conditions['defect_time'] = array('between' => array($start, $end));
		}

		// 출고 여부
		$conditions['defect_check'] = false;

		if(isset($search_word)){
			$conditions[] = " ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%' OR ".$this->defectModel->getAlias().".defect_memo LIKE '%".$search_word."%') ";
		}

		$totals = $this->defectModel->gets($conditions);
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

		// 불량수량 및 금액 합계
		$sum_defect_qty = 0;
		$sum_defect_amount = 0;
		$sum_defect_tax = 0;
		$sum_defect_tamount = 0;

		if($totals){
			foreach($totals as $defect){
				$sum_defect_qty+= $defect['defect_qty'];
				$sum_defect_amount+= $defect['defect_amount'];
				$sum_defect_tax+= $defect['defect_tax'];
				$sum_defect_tamount+= $defect['defect_tamount'];
			}
		}

		$this->clean['sum_defect_qty'] = $sum_defect_qty;
		$this->clean['sum_defect_amount'] = $sum_defect_amount;
		$this->clean['sum_defect_tax'] = $sum_defect_tax;
		$this->clean['sum_defect_tamount'] = $sum_defect_tamount;

		// 불량항목 불러오기
		$defects = $this->defectModel->gets(
			$conditions,
			null,
			array('idx' => 'DESC'),
			$page_limit,
			$page_offset
		);
		$this->clean['defects'] = $defects;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('defect_index.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function ajax_add_defect(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 날짜 바꾸기
			if(isset($_POST['defect_time']) && preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_POST['defect_time'])){
				list($year, $month, $date) = explode('-', $_POST['defect_time']);
					$clean['defect_time'] = mktime(0,0,0,$month,$date,$year);
			}else{
				throw new Exception('불량일자가 올바르지 않습니다.');
			}

			$defect_idx = $this->defectModel->add(
				array(
					'company_idx' => $this->mycompany['idx'],
					'defect_time' => $clean['defect_time'],
					'category_idx' => $_POST['category_idx'],
					'product_idx' => $_POST['product_size'], // product_size가 idx
					'defect_qty' => str_replace(",", "", $_POST['defect_qty']),
					'defect_price' => str_replace(",", "", $_POST['defect_price']),
					'defect_amount' => str_replace(",", "", $_POST['defect_amount']),
					'defect_tax' => str_replace(",", "", $_POST['defect_tax']),
					'defect_tamount' => str_replace(",", "", $_POST['defect_tamount']),
					'defect_memo' => $_POST['defect_memo']
				)
			);
			if(!$defect_idx) throw new Exception('불량항목 추가에 실패하였습니다.');
			

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $defect_idx));
		exit;
	}

	public function ajax_edit_defect(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 불량항목 불러오기
			$defect = $this->defectModel->get($_POST['defect_idx']);
			if(!$defect) throw new Exception('불량항목이 없습니다.');
			if($defect['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			// 날짜 바꾸기
			if(isset($_POST['defect_time']) && preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_POST['defect_time'])){
				list($year, $month, $date) = explode('-', $_POST['defect_time']);
					$clean['defect_time'] = mktime(0,0,0,$month,$date,$year);
			}else{
				throw new Exception('불량일자가 올바르지 않습니다.');
			}

			$result = $this->defectModel->edit(
				$defect['idx'],
				array(
					'defect_time' => $clean['defect_time'],
					'category_idx' => $_POST['category_idx'],
					'product_idx' => $_POST['product_size'], // product_size가 idx
					'defect_qty' => str_replace(",", "", $_POST['defect_qty']),
					'defect_price' => str_replace(",", "", $_POST['defect_price']),
					'defect_amount' => str_replace(",", "", $_POST['defect_amount']),
					'defect_tax' => str_replace(",", "", $_POST['defect_tax']),
					'defect_tamount' => str_replace(",", "", $_POST['defect_tamount']),
					'defect_memo' => $_POST['defect_memo']
				)
			);
			if(!$result) throw new Exception('불량항목 수정에 실패하였습니다.');
			

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

	public function ajax_output_defects(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 처리용 idx
			$process_defect_idx = array();
			// 처리용에 담기
			if(is_array($_POST['defect_idx'])) $process_defect_idx = $_POST['defect_idx'];
			else $process_defect_idx[] = $_POST['defect_idx'];

			foreach($process_defect_idx as $defect_idx){

				// 불량항목 불러오기
				$defect = $this->defectModel->get($defect_idx);
				if(!$defect) throw new Exception('불량항목이 없습니다.');
				if($defect['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');
				if($defect['defect_check']) throw new Exception('['.$defect['idx'].'] 항목은 이미 출고되어 있습니다.');

				$output_time = time();

				$result = $this->defectModel->edit($defect['idx'], array('defect_check' => $output_time));
				if(!$result) throw new Exception('불량항목 출고에 실패하였습니다.');

				// 출고 생성
				$output_idx = $this->outputModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'output_time' => $output_time,
						'category_idx' => $defect['category_idx'],
						'product_idx' => $defect['product_idx'],
						'output_qty' => $defect['defect_qty'],
						'output_price' => $defect['defect_price'],
						'output_amount' => $defect['defect_amount'],
						'output_tax' => $defect['defect_tax'],
						'output_tamount' => $defect['defect_tamount'],
						'output_memo' => '불량반출자동기입'
					)
				);
				if(!$output_idx) throw new Exception('출고데이터 생성에 실패하였습니다.');
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

	public function ajax_delete_defects(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 처리용 idx
			$process_defect_idx = array();
			// 처리용에 담기
			if(is_array($_POST['defect_idx'])) $process_defect_idx = $_POST['defect_idx'];
			else $process_defect_idx[] = $_POST['defect_idx'];

			foreach($process_defect_idx as $defect_idx){

				// 불량항목 불러오기
				$defect = $this->defectModel->get($defect_idx);
				if(!$defect) throw new Exception('불량항목이 없습니다.');
				if($defect['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

				$result = $this->defectModel->delete($defect['idx']);
				if(!$result) throw new Exception('불량항목 삭제에 실패하였습니다.');
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

	public function ajax_get_category_colors(){

		try{

			// 카테고리 불러오기
			$category = $this->categoryModel->get($_POST['category_idx']);
			if(!$category) throw new Exception('제품정보를 찾을 수 없습니다.');
			if($category['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			$product_colors = $this->productModel->gets(
				array('category_idx' => $category['idx']),
				array('product_color'),
				array('product_color' => 'ASC')
			);

		}catch(Exception $e){
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;
		}

		echo json_encode(array('result' => 'true', 'datas' => $product_colors ? $product_colors : array()));
		exit;
	}

	public function ajax_get_category_color_sizes(){

		try{

			// 카테고리 불러오기
			$category = $this->categoryModel->get($_POST['category_idx']);
			if(!$category) throw new Exception('제품정보를 찾을 수 없습니다.');
			if($category['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			$product_sizes = $this->productModel->gets(
				array('category_idx' => $category['idx'], 'product_color' => $_POST['product_color']),
				null,
				array('product_size' => 'ASC')
			);

		}catch(Exception $e){
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;
		}

		echo json_encode(array('result' => 'true', 'datas' => $product_sizes ? $product_sizes : array()));
		exit;
	}

	public function ajax_excel_defect(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			foreach($_POST['excel_no'] as $i){

				$product = $this->productModel->get(array('company_idx' => $this->mycompany['idx'], 'product_code' => $_POST['excel_code'][$i]));
				if(!$product) throw new Exception('품목정보를 찾을 수 없습니다.');

				list($year, $month, $date) = explode('-', $_POST['excel_time'][$i]);
				$defect_time = mktime(0,0,0,$month,$date,$year);

				$defect_idx = $this->defectModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'defect_time' => $defect_time,
						'category_idx' => $product['category_idx'],
						'product_idx' => $product['idx'],
						'defect_qty' => str_replace(",", "", $_POST['excel_qty'][$i]),
						'defect_price' => 0,
						'defect_amount' => 0,
						'defect_tax' => 0,
						'defect_tamount' => 0,
						'defect_store' => null,
						'defect_delivery_charge' => $_POST['excel_delivery_charge'][$i],
						'defect_order_no' => $_POST['excel_order_no'][$i],
						'defect_customer_name' => $_POST['excel_customer_name'][$i],
						'defect_customer_tel' => $_POST['excel_customer_tel'][$i],
						'defect_customer_zipcode' => $_POST['excel_customer_zipcode'][$i],
						'defect_customer_address' => $_POST['excel_customer_address'][$i],
						'defect_customer_message' => null,
						'defect_product_name' => $_POST['excel_product_name'][$i],
						'defect_options' => $_POST['excel_options'][$i],
					)
				);
				if(!$defect_idx) throw new Exception('불량항목 추가에 실패하였습니다.');

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