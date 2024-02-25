<?
if(!defined('DOTORI_CONTROLLER')) exit;
class defectModel Extends Model{

	public $alias = 'DF';
	public $use_file = false;

	public $select_fields = array(
		"defect_time",
		"defect_qty",
		"defect_price",
		"defect_amount",
		"defect_tax",
		"defect_tamount",
		"defect_memo",
		"defect_check",
		"defect_type"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->categoryModel->getFields().", ";
		$query.= " ".$this->productModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->categoryModel->getTable()." AS ".$this->categoryModel->getAlias();
		$query.= " ON ".$this->alias.".category_idx = ".$this->categoryModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->productModel->getTable()." AS ".$this->productModel->getAlias();
		$query.= " ON ".$this->alias.".product_idx = ".$this->productModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}

	public function getSumDefect($start, $end, $search_word = null){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->categoryModel->getFields().", ";
		$query.= " ".$this->productModel->getFields().", ";
		$query.= " SUM(".$this->alias.".defect_qty) AS defect_qty FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->categoryModel->getTable()." AS ".$this->categoryModel->getAlias();
		$query.= " ON ".$this->alias.".category_idx = ".$this->categoryModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->productModel->getTable()." AS ".$this->productModel->getAlias();
		$query.= " ON ".$this->alias.".product_idx = ".$this->productModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		$query.= " AND ".$this->alias.".defect_check IS NULL ";
		$query.= " AND ".$this->alias.".defect_time BETWEEN ".$start." AND ".$end." ";
		if($search_word){
			$query.= " AND ( ".$this->categoryModel->getAlias().".category_code LIKE '%".$search_word."%' OR ".$this->categoryModel->getAlias().".category_name LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_code LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_color LIKE '%".$search_word."%' OR ".$this->productModel->getAlias().".product_size LIKE '%".$search_word."%') ";
		}
		$query.= " GROUP BY ".$this->alias.".product_idx ";

		$result = $this->db->query($query);
		if($result){
			$datas = array();
			while($data = $result->fetch_assoc()){
				$datas[$data['product_idx']] = $data;
			}
			return $datas;
		}else{
			return false;
		}
	}

}

?>