<?
if(!defined('DOTORI_CONTROLLER')) exit;
class productModel Extends Model{

	public $alias = 'PD';
	public $use_file = false;

	public $select_fields = array(
		"product_code",
		"product_color",
		"product_size",
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->categoryModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->categoryModel->getTable()." AS ".$this->categoryModel->getAlias();
		$query.= " ON ".$this->alias.".category_idx = ".$this->categoryModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}

	/*public function getInventory($start, $end, $search_word = null){

		$invens = $this->betweenInventory($start, $end, $search_word);
		$befores = $this->beforeInventory($start, $end, $search_word);
		foreach(array_keys($invens) as $i){
			if(!$invens[$i]['basic_qty']
				&& !$invens[$i]['po_qty'] 
				&& !$invens[$i]['input_qty'] 
				&& !$invens[$i]['output_qty'] 
				&& !$invens[$i]['defect_qty']
			){
				unset($invens[$i]);
				continue;
			}
			$invens[$i]['basic'] = $invens[$i]['basic_qty'] + $befores[$i]['input_qty'] - $befores[$i]['output_qty'] - $befores[$i]['defect_qty'];
			$invens[$i]['po'] = $invens[$i]['po_qty'];
			$invens[$i]['input'] = $invens[$i]['input_qty'];
			$invens[$i]['output'] = $invens[$i]['output_qty'];
			$invens[$i]['defect'] = $invens[$i]['defect_qty'];
			$invens[$i]['stock'] = $invens[$i]['basic'] + $invens[$i]['input'] - $invens[$i]['output'] - $invens[$i]['defect'];
		}
		return $invens;
	}

	public function getInventoryForHome($start, $end, $search_word = null){
		$invens = $this->betweenInventory($start, $end, $search_word);
		$befores = $this->beforeInventory($start, $end, $search_word);
		foreach(array_keys($invens) as $i){
			if(!$invens[$i]['po_qty'] 
				&& !$invens[$i]['input_qty'] 
				&& !$invens[$i]['output_qty'] 
				&& !$invens[$i]['defect_qty']
			){
				unset($invens[$i]);
				continue;
			}
			$invens[$i]['basic'] = $invens[$i]['basic_qty'] + $befores[$i]['input_qty'] - $befores[$i]['output_qty'] - $befores[$i]['defect_qty'];
			$invens[$i]['po'] = $invens[$i]['po_qty'];
			$invens[$i]['input'] = $invens[$i]['input_qty'];
			$invens[$i]['output'] = $invens[$i]['output_qty'];
			$invens[$i]['defect'] = $invens[$i]['defect_qty'];
			$invens[$i]['stock'] = $invens[$i]['basic'] + $invens[$i]['input'] - $invens[$i]['output'] - $invens[$i]['defect'];
		}
		return $invens;
	}

	private function betweenInventory($start, $end, $search_word = null){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->categoryModel->getFields().", ";
		$query.= " SUM(".$this->poModel->getAlias().".po_qty) AS po_qty, ";
		$query.= " SUM(".$this->inputModel->getAlias().".input_qty) AS input_qty, ";
		$query.= " SUM(".$this->outputModel->getAlias().".output_qty) AS output_qty, ";
		$query.= " SUM(".$this->defectModel->getAlias().".defect_qty) AS defect_qty ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->categoryModel->getTable()." AS ".$this->categoryModel->getAlias();
		$query.= " ON ".$this->alias.".category_idx = ".$this->categoryModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->poModel->getTable()." AS ".$this->poModel->getAlias();
		$query.= " ON ".$this->alias.".idx = ".$this->poModel->getAlias().".product_idx AND ".$this->poModel->getAlias().".po_check IS NULL ";
		$query.= " LEFT JOIN ".$this->inputModel->getTable()." AS ".$this->inputModel->getAlias();
		$query.= " ON ".$this->alias.".idx = ".$this->inputModel->getAlias().".product_idx AND ".$this->inputModel->getAlias().".input_time > ".$start." AND ".$this->inputModel->getAlias().".input_time < ".$end." ";
		$query.= " LEFT JOIN ".$this->outputModel->getTable()." AS ".$this->outputModel->getAlias();
		$query.= " ON ".$this->alias.".idx = ".$this->outputModel->getAlias().".product_idx AND ".$this->outputModel->getAlias().".output_time > ".$start." AND ".$this->outputModel->getAlias().".output_time < ".$end." ";
		$query.= " LEFT JOIN ".$this->defectModel->getTable()." AS ".$this->defectModel->getAlias();
		$query.= " ON ".$this->alias.".idx = ".$this->defectModel->getAlias().".product_idx AND ".$this->defectModel->getAlias().".defect_time > ".$start." AND ".$this->defectModel->getAlias().".defect_time < ".$end." ";
		$query.= " WHERE 1 ";

		if($search_word){
			$query.= " AND ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%') ";
		}

		$query.= " GROUP BY ".$this->alias.".idx ";

		$result = $this->db->query($query);
		if($result){
			$datas = array();
			while($data = $result->fetch_assoc()){
				$datas[$data['idx']] = $data;
			}
			return $datas;
		}else{
			return false;
		}
	}

	private function beforeInventory($start, $end, $search_word = null){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->categoryModel->getFields().", ";
		$query.= " SUM(".$this->inputModel->getAlias().".input_qty) AS before_input_qty, ";
		$query.= " SUM(".$this->outputModel->getAlias().".output_qty) AS before_output_qty, ";
		$query.= " SUM(".$this->defectModel->getAlias().".defect_qty) AS before_defect_qty ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->categoryModel->getTable()." AS ".$this->categoryModel->getAlias();
		$query.= " ON ".$this->alias.".category_idx = ".$this->categoryModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->inputModel->getTable()." AS ".$this->inputModel->getAlias();
		$query.= " ON ".$this->alias.".idx = ".$this->inputModel->getAlias().".product_idx AND ".$this->inputModel->getAlias().".input_time < ".$start." ";
		$query.= " LEFT JOIN ".$this->outputModel->getTable()." AS ".$this->outputModel->getAlias();
		$query.= " ON ".$this->alias.".idx = ".$this->outputModel->getAlias().".product_idx AND ".$this->outputModel->getAlias().".output_time < ".$start." ";
		$query.= " LEFT JOIN ".$this->defectModel->getTable()." AS ".$this->defectModel->getAlias();
		$query.= " ON ".$this->alias.".idx = ".$this->defectModel->getAlias().".product_idx AND ".$this->defectModel->getAlias().".defect_time < ".$start." ";
		$query.= " WHERE 1 ";

		if($search_word){
			$query.= " AND ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%') ";
		}

		$query.= " GROUP BY ".$this->alias.".idx ";

		$result = $this->db->query($query);
		if($result){
			$datas = array();
			while($data = $result->fetch_assoc()){
				$datas[$data['idx']] = $data;
			}
			return $datas;
		}else{
			return false;
		}
	}*/




	/*public function getInventory($start,$end,$search_word = null){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->categoryModel->getFields().", ";
		$query.= " (SELECT SUM(po_qty) FROM ".$this->poModel->getTable()." WHERE product_idx = ".$this->alias.".idx AND po_check IS NULL) as po_qty, ";
		$query.= " (SELECT SUM(input_qty) FROM ".$this->inputModel->getTable()." WHERE product_idx = ".$this->alias.".idx AND (input_time < ".$start.")) as before_input_qty, ";
		$query.= " (SELECT SUM(output_qty) FROM ".$this->outputModel->getTable()." WHERE product_idx = ".$this->alias.".idx AND (output_time < ".$start.")) as before_output_qty, ";
		$query.= " (SELECT SUM(defect_qty) FROM ".$this->defectModel->getTable()." WHERE product_idx = ".$this->alias.".idx AND (defect_time < ".$start.")) as before_defect_qty, ";
		$query.= " (SELECT SUM(input_qty) FROM ".$this->inputModel->getTable()." WHERE product_idx = ".$this->alias.".idx AND (input_time BETWEEN ".$start." AND ".$end.")) as input_qty, ";
		$query.= " (SELECT SUM(output_qty) FROM ".$this->outputModel->getTable()." WHERE product_idx = ".$this->alias.".idx AND (output_time BETWEEN ".$start." AND ".$end.")) as output_qty, ";
		$query.= " (SELECT SUM(defect_qty) FROM ".$this->defectModel->getTable()." WHERE product_idx = ".$this->alias.".idx AND (defect_time BETWEEN ".$start." AND ".$end.")) as defect_qty ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->categoryModel->getTable()." AS ".$this->categoryModel->getAlias();
		$query.= " ON ".$this->alias.".category_idx = ".$this->categoryModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		if($search_word){
			$query.= " AND ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%') ";
		}

		$result = $this->db->query($query);
		if($result){
			$datas = array();
			while($data = $result->fetch_assoc()){
				$datas[] = $data;
			}
			return $datas;
		}else{
			return false;
		}
	}*/

}

?>