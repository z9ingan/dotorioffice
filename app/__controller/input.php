<?
if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoricodeController.php"; // parent load
class input extends dotoricodeController{

	public function __construct(){

		parent::__construct();

	}

	public function excel(){

		if(!isset($_POST['excel_content']) || !$_POST['excel_content']) $this->error('입고할 데이터가 없습니다.');

		// 입력데이터
		$rows = explode("\n", stripslashes($_POST['excel_content']));
		
		// 입고용 데이터 초기화
		$excels = array();

		// 줄
		for($i=0;$i<count($rows);$i++){
			if(!$rows[$i]) continue; // 비어있으면 스킵...
			$row = explode("\t", $rows[$i]);

			// 제품코드 조회
			$product = $this->productModel->get(array('company_idx' => $this->mycompany['idx'], 'product_code' => $row[0]));
			
			// 담아주기
			$excels[] = array(
				'product' => $product,
				'excel_code' => $row[0],
				'excel_name' => $row[1],
				'excel_qty' => str_replace(",", "", $row[2]),
				'excel_price' => str_replace(",", "", $row[3]),
				'excel_amount' => str_replace(",", "", $row[4]),
				'excel_tax' => str_replace(",", "", $row[5]),
				'excel_tamount' => str_replace(",", "", $row[4]) + str_replace(",", "", $row[5])
			);

			$this->clean['excels'] = $excels;
			$this->clean['input_time'] = $_POST['input_time'];
		}

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('input_excel.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function index($input_idx = null){

		// 입고항목 불러오기 (수정모드)
		if($input_idx){
			$input = $this->inputModel->get($input_idx);
			$product_colors = $this->productModel->gets(
				array('category_idx' => $input['category_idx']),
				array('product_color'),
				array('product_color' => 'ASC')
			);
			$product_sizes = $this->productModel->gets(
				array('category_idx' => $input['category_idx'], 'product_color' => $input['product_color']),
				null,
				array('product_size' => 'ASC')
			);
			
			$this->clean['input'] = $input;
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
			$conditions['input_time'] = array('between' => array($start, $end));
		}

		if(isset($search_word)){
			$conditions[] = " ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%' OR ".$this->inputModel->getAlias().".input_memo LIKE '%".$search_word."%') ";
		}

		$totals = $this->inputModel->gets($conditions);
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

		// 입고수량 및 금액 합계
		$sum_input_qty = 0;
		$sum_input_amount = 0;
		$sum_input_tax = 0;
		$sum_input_tamount = 0;

		if($totals){
			foreach($totals as $input){
				$sum_input_qty+= $input['input_qty'];
				$sum_input_amount+= $input['input_amount'];
				$sum_input_tax+= $input['input_tax'];
				$sum_input_tamount+= $input['input_tamount'];
			}
		}

		$this->clean['sum_input_qty'] = $sum_input_qty;
		$this->clean['sum_input_amount'] = $sum_input_amount;
		$this->clean['sum_input_tax'] = $sum_input_tax;
		$this->clean['sum_input_tamount'] = $sum_input_tamount;

		// 입고항목 불러오기
		$inputs = $this->inputModel->gets(
			$conditions,
			null,
			array('idx' => 'DESC'),
			$page_limit,
			$page_offset
		);
		$this->clean['inputs'] = $inputs;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('input_index.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function ajax_add_input(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 날짜 바꾸기
			if(isset($_POST['input_time']) && preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_POST['input_time'])){
				list($year, $month, $date) = explode('-', $_POST['input_time']);
					$clean['input_time'] = mktime(0,0,0,$month,$date,$year);
			}else{
				throw new Exception('입고일자가 올바르지 않습니다.');
			}

			$input_idx = $this->inputModel->add(
				array(
					'company_idx' => $this->mycompany['idx'],
					'input_time' => $clean['input_time'],
					'category_idx' => $_POST['category_idx'],
					'product_idx' => $_POST['product_size'], // product_size가 idx
					'input_qty' => str_replace(",", "", $_POST['input_qty']),
					'input_price' => str_replace(",", "", $_POST['input_price']),
					'input_amount' => str_replace(",", "", $_POST['input_amount']),
					'input_tax' => str_replace(",", "", $_POST['input_tax']),
					'input_tamount' => str_replace(",", "", $_POST['input_tamount']),
					'input_memo' => $_POST['input_memo']
				)
			);
			if(!$input_idx) throw new Exception('입고항목 추가에 실패하였습니다.');
			

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $input_idx));
		exit;
	}

	public function ajax_edit_input(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 입고항목 불러오기
			$input = $this->inputModel->get($_POST['input_idx']);
			if(!$input) throw new Exception('입고항목이 없습니다.');
			if($input['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

			// 날짜 바꾸기
			if(isset($_POST['input_time']) && preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_POST['input_time'])){
				list($year, $month, $date) = explode('-', $_POST['input_time']);
					$clean['input_time'] = mktime(0,0,0,$month,$date,$year);
			}else{
				throw new Exception('입고일자가 올바르지 않습니다.');
			}

			$result = $this->inputModel->edit(
				$input['idx'],
				array(
					'input_time' => $clean['input_time'],
					'category_idx' => $_POST['category_idx'],
					'product_idx' => $_POST['product_size'], // product_size가 idx
					'input_qty' => str_replace(",", "", $_POST['input_qty']),
					'input_price' => str_replace(",", "", $_POST['input_price']),
					'input_amount' => str_replace(",", "", $_POST['input_amount']),
					'input_tax' => str_replace(",", "", $_POST['input_tax']),
					'input_tamount' => str_replace(",", "", $_POST['input_tamount']),
					'input_memo' => $_POST['input_memo']
				)
			);
			if(!$result) throw new Exception('입고항목 수정에 실패하였습니다.');
			

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

	public function ajax_delete_inputs(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 처리용 idx
			$process_input_idx = array();
			// 처리용에 담기
			if(is_array($_POST['input_idx'])) $process_input_idx = $_POST['input_idx'];
			else $process_input_idx[] = $_POST['input_idx'];

			foreach($process_input_idx as $input_idx){

				// 입고항목 불러오기
				$input = $this->inputModel->get($input_idx);
				if(!$input) throw new Exception('입고항목이 없습니다.');
				if($input['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');

				// 발주항목의 체크 풀어주기
				if($input['po_idx']){
					$result = $this->poModel->edit($input['po_idx'], array('po_check' => null));
					if(!$result) throw new Exception('발주항목 수정에 실패하였습니다.');
				}

				$result = $this->inputModel->delete($input['idx']);
				if(!$result) throw new Exception('입고항목 삭제에 실패하였습니다.');
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

	public function ajax_excel_input(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			foreach($_POST['excel_no'] as $i){

				$product = $this->productModel->get(array('company_idx' => $this->mycompany['idx'], 'product_code' => $_POST['excel_code'][$i]));
				if(!$product) throw new Exception('품목정보를 찾을 수 없습니다.');

				list($year, $month, $date) = explode('-', $_POST['excel_time'][$i]);
				$input_time = mktime(0,0,0,$month,$date,$year);

				$input_idx = $this->inputModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'input_time' => $input_time,
						'category_idx' => $product['category_idx'],
						'product_idx' => $product['idx'],
						'input_qty' => str_replace(",", "", $_POST['excel_qty'][$i]),
						'input_price' => str_replace(",", "", $_POST['excel_price'][$i]),
						'input_amount' => str_replace(",", "", $_POST['excel_amount'][$i]),
						'input_tax' => str_replace(",", "", $_POST['excel_tax'][$i]),
						'input_tamount' => str_replace(",", "", $_POST['excel_tamount'][$i])
					)
				);
				if(!$input_idx) throw new Exception('입고항목 추가에 실패하였습니다.');

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