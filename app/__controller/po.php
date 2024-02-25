<?
if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoricodeController.php"; // parent load
class po extends dotoricodeController{

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
			$this->clean['po_time'] = $_POST['po_time'];
		}

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('po_excel.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function index($po_idx = null){

		// 발주항목 불러오기 (수정모드)
		if($po_idx){
			$porder = $this->poModel->get($po_idx);
			$product_colors = $this->productModel->gets(
				array('category_idx' => $porder['category_idx']),
				array('product_color'),
				array('product_color' => 'ASC')
			);
			$product_sizes = $this->productModel->gets(
				array('category_idx' => $porder['category_idx'], 'product_color' => $porder['product_color']),
				null,
				array('product_size' => 'ASC')
			);
			
			$this->clean['porder'] = $porder;
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
		// 입고여부
		if(isset($_GET['status']) && in_array($_GET['status'], array('all', 'po', 'input'))){
			$status = $_GET['status'];
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
			$conditions['po_time'] = array('between' => array($start, $end));
		}

		if(isset($status)){
			switch($status){
				case 'po':		$conditions['po_check'] = false; break;
				case 'input':	$conditions['po_check'] = true; break;
			}
		}

		if(isset($search_word)){
			$conditions[] = " ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%' OR ".$this->poModel->getAlias().".po_memo LIKE '%".$search_word."%') ";
		}

		$totals = $this->poModel->gets($conditions);
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

		// 발주수량 및 금액 합계
		$sum_po_qty = 0;
		$sum_po_amount = 0;
		$sum_po_tax = 0;
		$sum_po_tamount = 0;

		if($totals){
			foreach($totals as $po){
				$sum_po_qty+= $po['po_qty'];
				$sum_po_amount+= $po['po_amount'];
				$sum_po_tax+= $po['po_tax'];
				$sum_po_tamount+= $po['po_tamount'];
			}
		}

		$this->clean['sum_po_qty'] = $sum_po_qty;
		$this->clean['sum_po_amount'] = $sum_po_amount;
		$this->clean['sum_po_tax'] = $sum_po_tax;
		$this->clean['sum_po_tamount'] = $sum_po_tamount;

		// 발주항목 불러오기
		$pos = $this->poModel->gets(
			$conditions,
			null,
			array('idx' => 'DESC'),
			$page_limit,
			$page_offset
		);
		$this->clean['pos'] = $pos;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('po_index.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function ajax_add_po(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 날짜 바꾸기
			if(isset($_POST['po_time']) && preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_POST['po_time'])){
				list($year, $month, $date) = explode('-', $_POST['po_time']);
					$clean['po_time'] = mktime(0,0,0,$month,$date,$year);
			}else{
				throw new Exception('발주일자가 올바르지 않습니다.');
			}

			$po_idx = $this->poModel->add(
				array(
					'company_idx' => $this->mycompany['idx'],
					'po_time' => $clean['po_time'],
					'category_idx' => $_POST['category_idx'],
					'product_idx' => $_POST['product_size'], // product_size가 idx
					'po_qty' => str_replace(",", "", $_POST['po_qty']),
					'po_price' => str_replace(",", "", $_POST['po_price']),
					'po_amount' => str_replace(",", "", $_POST['po_amount']),
					'po_tax' => str_replace(",", "", $_POST['po_tax']),
					'po_tamount' => str_replace(",", "", $_POST['po_tamount']),
					'po_memo' => $_POST['po_memo']
				)
			);
			if(!$po_idx) throw new Exception('발주항목 추가에 실패하였습니다.');
			

		}catch(Exception $e){

			$this->db->rollback(); // 실패, 롤백

			header('Content-Type: application/json');
			echo json_encode(array('result' => 'false', 'message' => $e->getMessage()));
			exit;

		}

		$this->db->commit(); // 성공, 실행
		
		header('Content-Type: application/json');
		echo json_encode(array('result' => 'true', 'idx' => $po_idx));
		exit;
	}

	public function ajax_edit_po(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 발주항목 불러오기
			$po = $this->poModel->get($_POST['po_idx']);
			if(!$po) throw new Exception('발주항목이 없습니다.');
			if($po['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');
			if($po['po_check']) throw new Exception('이미 입고된 항목은 수정할 수 없습니다.');

			// 날짜 바꾸기
			if(isset($_POST['po_time']) && preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_POST['po_time'])){
				list($year, $month, $date) = explode('-', $_POST['po_time']);
					$clean['po_time'] = mktime(0,0,0,$month,$date,$year);
			}else{
				throw new Exception('발주일자가 올바르지 않습니다.');
			}

			$result = $this->poModel->edit(
				$po['idx'],
				array(
					'po_time' => $clean['po_time'],
					'category_idx' => $_POST['category_idx'],
					'product_idx' => $_POST['product_size'], // product_size가 idx
					'po_qty' => str_replace(",", "", $_POST['po_qty']),
					'po_price' => str_replace(",", "", $_POST['po_price']),
					'po_amount' => str_replace(",", "", $_POST['po_amount']),
					'po_tax' => str_replace(",", "", $_POST['po_tax']),
					'po_tamount' => str_replace(",", "", $_POST['po_tamount']),
					'po_memo' => $_POST['po_memo']
				)
			);
			if(!$result) throw new Exception('발주항목 수정에 실패하였습니다.');
			

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

	public function ajax_input_pos(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 처리용 idx
			$process_po_idx = array();
			// 처리용에 담기
			if(is_array($_POST['po_idx'])) $process_po_idx = $_POST['po_idx'];
			else $process_po_idx[] = $_POST['po_idx'];

			foreach($process_po_idx as $po_idx){

				// 발주항목 불러오기
				$po = $this->poModel->get($po_idx);
				if(!$po) throw new Exception('발주항목이 없습니다.');
				if($po['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');
				if($po['po_check']) throw new Exception('['.$po['idx'].'] 항목은 이미 입고되어 있습니다.');

				$input_time = time();

				$result = $this->poModel->edit($po['idx'], array('po_check' => $input_time));
				if(!$result) throw new Exception('발주항목 입고에 실패하였습니다.');

				// 입고 생성
				$input_idx = $this->inputModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'input_time' => $input_time,
						'po_idx' => $po['idx'],
						'category_idx' => $po['category_idx'],
						'product_idx' => $po['product_idx'],
						'input_qty' => $po['po_qty'],
						'input_price' => $po['po_price'],
						'input_amount' => $po['po_amount'],
						'input_tax' => $po['po_tax'],
						'input_tamount' => $po['po_tamount'],
						'input_memo' => $po['po_memo']
					)
				);
				if(!$input_idx) throw new Exception('입고데이터 생성에 실패하였습니다.');
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

	public function ajax_delete_pos(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			// 처리용 idx
			$process_po_idx = array();
			// 처리용에 담기
			if(is_array($_POST['po_idx'])) $process_po_idx = $_POST['po_idx'];
			else $process_po_idx[] = $_POST['po_idx'];

			foreach($process_po_idx as $po_idx){

				// 발주항목 불러오기
				$po = $this->poModel->get($po_idx);
				if(!$po) throw new Exception('발주항목이 없습니다.');
				if($po['company_idx'] != $this->mycompany['idx']) throw new Exception('올바르지 않습니다.');
				if($po['po_check']) throw new Exception('['.$po['idx'].'] 입고된 항목은 삭제할 수 없습니다.');

				$result = $this->poModel->delete($po['idx']);
				if(!$result) throw new Exception('발주항목 삭제에 실패하였습니다.');
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

	public function ajax_excel_po(){
		
		try{
			// 트랜잭션 시작
			$this->db->autocommit(false);

			foreach($_POST['excel_no'] as $i){

				$product = $this->productModel->get(array('company_idx' => $this->mycompany['idx'], 'product_code' => $_POST['excel_code'][$i]));
				if(!$product) throw new Exception('품목정보를 찾을 수 없습니다.');

				list($year, $month, $date) = explode('-', $_POST['excel_time'][$i]);
				$po_time = mktime(0,0,0,$month,$date,$year);

				$po_idx = $this->poModel->add(
					array(
						'company_idx' => $this->mycompany['idx'],
						'po_time' => $po_time,
						'category_idx' => $product['category_idx'],
						'product_idx' => $product['idx'],
						'po_qty' => str_replace(",", "", $_POST['excel_qty'][$i]),
						'po_price' => str_replace(",", "", $_POST['excel_price'][$i]),
						'po_amount' => str_replace(",", "", $_POST['excel_amount'][$i]),
						'po_tax' => str_replace(",", "", $_POST['excel_tax'][$i]),
						'po_tamount' => str_replace(",", "", $_POST['excel_tamount'][$i])
					)
				);
				if(!$po_idx) throw new Exception('입고항목 추가에 실패하였습니다.');

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