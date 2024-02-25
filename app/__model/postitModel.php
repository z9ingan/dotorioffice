<?
if(!defined('DOTORI_CONTROLLER')) exit;
class postitModel Extends Model{

	public $alias = 'PI';
	public $use_file = false;

	public $select_fields = array(
		"postit_memo"
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