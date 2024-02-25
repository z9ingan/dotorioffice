<?
if(!defined('DOTORI_CONTROLLER')) exit;
class notefileModel Extends Model{

	public $alias = 'NTF';
	public $use_file = true;

	public $select_fields = array(
		"filename",
		"filesize",
		"filetype",
		"fakename",
		"fileext");

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".* ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " WHERE 1 ";
		return $query;
	}
}

?>