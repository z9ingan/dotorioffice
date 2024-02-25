<?
if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoricodeController.php"; // parent load
class inven extends dotoricodeController{

	public function __construct(){

		parent::__construct();

	}

	public function index(){

		// 카테고리 불러오기
		$categorys = $this->categoryModel->gets(array('company_idx' => $this->mycompany['idx']));
		$this->clean['categorys'] = $categorys;

		// 날짜
		// 시작날짜
		if(isset($_GET['start']) 
		&& isset($_GET['end']) 
		&& preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_GET['start']) 
		&& preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_GET['end'])){
			list($year, $month, $date) = explode('-', $_GET['start']);
			$start = mktime(0,0,0,$month, $date, $year);
			list($year, $month, $date) = explode('-', $_GET['end']);
			$end = mktime(23,59,59,$month, $date, $year);
		}else{
			$start = mktime(0,0,0,1,1,2020);//mktime(0,0,0,date('n'),1,date('Y'));
			$end = time();//mktime(23,59,59,date('n'),date('t'),date('Y'));
		}

		$this->clean['start'] = $start;
		$this->clean['end'] = $end;

		// 검색
		$conditions = array();
		$conditions['company_idx'] = $this->mycompany['idx'];

		// 검색어
		if(isset($_GET['search_word']) && $_GET['search_word']){
			$search_word = $_GET['search_word'];
			$conditions[] = " ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%') ";
		}else{
			$search_word = null;
		}
		$this->clean['search_word'] = $search_word;

		// 입고항목 불러오기
		$products = $this->productModel->gets($conditions);

		$pos = $this->poModel->getSumPo(null, null, $search_word);

		$inputs = $this->inputModel->getSumInput($start, $end, $search_word);
		$before_inputs = $this->inputModel->getSumInput(1, $start, $search_word);

		$outputs = $this->outputModel->getSumOutput($start, $end, $search_word);
		$before_outputs = $this->outputModel->getSumOutput(1, $start, $search_word);

		$defects = $this->defectModel->getSumDefect($start, $end, $search_word);
		$before_defects = $this->defectModel->getSumDefect(1, $start, $search_word);

		$invens = array();

		foreach($products as $pd){
			$i = $pd['idx'];
			$invens[$i] = $pd;
			$invens[$i]['basic'] = $pd['basic_qty'] + $before_inputs[$i]['input_qty'] - $before_outputs[$i]['output_qty'] - $before_defects[$i]['defect_qty'];
			$invens[$i]['input'] = $inputs[$i]['input_qty'];
			$invens[$i]['output'] = $outputs[$i]['output_qty'];
			$invens[$i]['defect'] = $defects[$i]['defect_qty'];
			$invens[$i]['stock'] = $invens[$i]['basic'] + $invens[$i]['input'] - $invens[$i]['output'] - $invens[$i]['defect'];

			if(!$invens[$i]['basic']
				&& !$invens[$i]['input']
				&& !$invens[$i]['output']
				&& !$invens[$i]['defect']
				&& !$invens[$i]['stock']
			){
				unset($invens[$i]);
			}
		}

		$this->clean['invens'] = $invens;

		$this->load->view('html_header.php', $this->clean);
		$this->load->view('inven_index.php', $this->clean);
		$this->load->view('html_footer.php', $this->clean);
	}

	public function file_inventory(){

		// 날짜
		// 시작날짜
		if(isset($_GET['start']) 
		&& isset($_GET['end']) 
		&& preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_GET['start']) 
		&& preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_GET['end'])){
			list($year, $month, $date) = explode('-', $_GET['start']);
			$start = mktime(0,0,0,$month, $date, $year);
			list($year, $month, $date) = explode('-', $_GET['end']);
			$end = mktime(23,59,59,$month, $date, $year);
		}else{
			$start = mktime(0,0,0,1,1,2020);//mktime(0,0,0,date('n'),1,date('Y'));
			$end = time();//mktime(23,59,59,date('n'),date('t'),date('Y'));
		}

		$this->clean['start'] = $start;
		$this->clean['end'] = $end;

		// 검색
		$conditions = array();
		$conditions['company_idx'] = $this->mycompany['idx'];

		// 검색어
		if(isset($_GET['search_word']) && $_GET['search_word']){
			$search_word = $_GET['search_word'];
			$conditions[] = " ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%') ";
		}else{
			$search_word = null;
		}
		$this->clean['search_word'] = $search_word;

		// 입고항목 불러오기
		$products = $this->productModel->gets($conditions);

		$pos = $this->poModel->getSumPo(null, null, $search_word);

		$inputs = $this->inputModel->getSumInput($start, $end, $search_word);
		$before_inputs = $this->inputModel->getSumInput(1, $start, $search_word);

		$outputs = $this->outputModel->getSumOutput($start, $end, $search_word);
		$before_outputs = $this->outputModel->getSumOutput(1, $start, $search_word);

		$defects = $this->defectModel->getSumDefect($start, $end, $search_word);
		$before_defects = $this->defectModel->getSumDefect(1, $start, $search_word);

		$invens = array();

		foreach($products as $pd){
			$i = $pd['idx'];
			$invens[$i] = $pd;
			$invens[$i]['basic'] = $pd['basic_qty'] + $before_inputs[$i]['input_qty'] - $before_outputs[$i]['output_qty'] - $before_defects[$i]['defect_qty'];
			$invens[$i]['input'] = $inputs[$i]['input_qty'];
			$invens[$i]['output'] = $outputs[$i]['output_qty'];
			$invens[$i]['defect'] = $defects[$i]['defect_qty'];
			$invens[$i]['stock'] = $invens[$i]['basic'] + $invens[$i]['input'] - $invens[$i]['output'] - $invens[$i]['defect'];

			if(!$invens[$i]['basic']
				&& !$invens[$i]['input']
				&& !$invens[$i]['output']
				&& !$invens[$i]['defect']
				&& !$invens[$i]['stock']
			){
				unset($invens[$i]);
			}
		}

		$this->clean['invens'] = $invens;

		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE")){
			$filename = urlencode(date('Ymd',$start).'-'.date('Ymd', $end).'.xls');
		}else{
			$filename = date('Ymd',$start).'-'.date('Ymd', $end).'.xls';
		}
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');
		$this->load->view('file_inventory.php', $this->clean);
		exit;

	}

	public function print_inventory(){

		// 날짜
		// 시작날짜
		if(isset($_GET['start']) 
		&& isset($_GET['end']) 
		&& preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_GET['start']) 
		&& preg_match('/^(19|20|21)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/',$_GET['end'])){
			list($year, $month, $date) = explode('-', $_GET['start']);
			$start = mktime(0,0,0,$month, $date, $year);
			list($year, $month, $date) = explode('-', $_GET['end']);
			$end = mktime(23,59,59,$month, $date, $year);
		}else{
			$start = mktime(0,0,0,1,1,2020);//mktime(0,0,0,date('n'),1,date('Y'));
			$end = time();//mktime(23,59,59,date('n'),date('t'),date('Y'));
		}

		$this->clean['start'] = $start;
		$this->clean['end'] = $end;

		// 검색
		$conditions = array();
		$conditions['company_idx'] = $this->mycompany['idx'];

		// 검색어
		if(isset($_GET['search_word']) && $_GET['search_word']){
			$search_word = $_GET['search_word'];
			$conditions[] = " ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%') ";
		}else{
			$search_word = null;
		}
		$this->clean['search_word'] = $search_word;

		// 입고항목 불러오기
		$products = $this->productModel->gets($conditions);

		$pos = $this->poModel->getSumPo(null, null, $search_word);

		$inputs = $this->inputModel->getSumInput($start, $end, $search_word);
		$before_inputs = $this->inputModel->getSumInput(1, $start, $search_word);

		$outputs = $this->outputModel->getSumOutput($start, $end, $search_word);
		$before_outputs = $this->outputModel->getSumOutput(1, $start, $search_word);

		$defects = $this->defectModel->getSumDefect($start, $end, $search_word);
		$before_defects = $this->defectModel->getSumDefect(1, $start, $search_word);

		$invens = array();

		foreach($products as $pd){
			$i = $pd['idx'];
			$invens[$i] = $pd;
			$invens[$i]['basic'] = $pd['basic_qty'] + $before_inputs[$i]['input_qty'] - $before_outputs[$i]['output_qty'] - $before_defects[$i]['defect_qty'];
			$invens[$i]['input'] = $inputs[$i]['input_qty'];
			$invens[$i]['output'] = $outputs[$i]['output_qty'];
			$invens[$i]['defect'] = $defects[$i]['defect_qty'];
			$invens[$i]['stock'] = $invens[$i]['basic'] + $invens[$i]['input'] - $invens[$i]['output'] - $invens[$i]['defect'];

			if(!$invens[$i]['basic']
				&& !$invens[$i]['input']
				&& !$invens[$i]['output']
				&& !$invens[$i]['defect']
				&& !$invens[$i]['stock']
			){
				unset($invens[$i]);
			}
		}

		$this->clean['invens'] = $invens;

		$this->load->view('print_header.php', $this->clean);
		$this->load->view('print_inventory.php', $this->clean);
		$this->load->view('print_footer.php', $this->clean);
	}

	public function ajax_load_input_history(){

		$inputs = $this->inputModel->gets(
			array(
				'company_idx' => $this->mycompany['idx'],
				'product_idx' => $_POST['product_idx'],
				'input_time' => array('between' => array($_POST['start'], $_POST['end']))
			),null, array('input_time' => 'DESC')
		);

		$this->clean['inputs'] = $inputs;

		$this->load->view('ajax_load_input_history.php', $this->clean);
	}

	public function ajax_load_output_history(){

		$outputs = $this->outputModel->gets(
			array(
				'company_idx' => $this->mycompany['idx'],
				'product_idx' => $_POST['product_idx'],
				'output_time' => array('between' => array($_POST['start'], $_POST['end']))
			),null, array('output_time' => 'DESC')
		);

		$this->clean['outputs'] = $outputs;

		$this->load->view('ajax_load_output_history.php', $this->clean);
	}

	public function ajax_load_defect_history(){

		$defects = $this->defectModel->gets(
			array(
				'company_idx' => $this->mycompany['idx'],
				'product_idx' => $_POST['product_idx'],
				'defect_time' => array('between' => array($_POST['start'], $_POST['end'])),
				'defect_check' => false
			),null, array('defect_time' => 'DESC')
		);

		$this->clean['defects'] = $defects;

		$this->load->view('ajax_load_defect_history.php', $this->clean);
	}

}

?>