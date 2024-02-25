<?
if(!defined('DOTORI_CONTROLLER')) exit;
class poModel Extends Model{

	public $alias = 'PO';
	public $use_file = false;

	public $select_fields = array(
		"po_time",
		"po_qty",
		"po_price",
		"po_amount",
		"po_tax",
		"po_tamount",
		"po_memo",
		"po_check"
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

	public function getSumPo($start = null, $end = null, $search_word = null){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->categoryModel->getFields().", ";
		$query.= " ".$this->productModel->getFields().", ";
		$query.= " SUM(".$this->alias.".po_qty) AS po_qty FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->categoryModel->getTable()." AS ".$this->categoryModel->getAlias();
		$query.= " ON ".$this->alias.".category_idx = ".$this->categoryModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->productModel->getTable()." AS ".$this->productModel->getAlias();
		$query.= " ON ".$this->alias.".product_idx = ".$this->productModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		$query.= " AND ".$this->alias.".po_check IS NULL ";
		if($start && $end){
			$query.= " AND ".$this->alias.".po_time BETWEEN ".$start." AND ".$end." ";
		}
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