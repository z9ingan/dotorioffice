<?
if(!defined('DOTORI_CONTROLLER')) exit;
class bdaccessModel Extends Model{

	public $alias = 'BDA';
	public $use_file = false;

	public $select_fields = array(
		"bdaccess_type"
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