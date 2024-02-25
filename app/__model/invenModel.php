<?
if(!defined('DOTORI_CONTROLLER')) exit;
class invenModel Extends Model{

	public $alias = 'IV';
	public $use_file = false;

	public $select_fields = array(
		"inven_price"
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

}

?>