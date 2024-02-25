<?
if(!defined('DOTORI_CONTROLLER')) exit;
class deptModel Extends Model{

	public $alias = 'DP';
	public $use_file = false;

	public $select_fields = array(
		"dept_name",
		"dept_memo"
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