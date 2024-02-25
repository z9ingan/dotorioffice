<?
if(!defined('DOTORI_CONTROLLER')) exit;
class categoryModel Extends Model{

	public $alias = 'CT';
	public $use_file = false;

	public $select_fields = array(
		"category_code",
		"category_name"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".* ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " WHERE 1 ";
		return $query;
	}

}

?>