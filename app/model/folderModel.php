<?
if(!defined('DOTORI_CONTROLLER')) exit;
class folderModel Extends Model{

	public $alias = 'FD';
	public $use_file = false;

	public $select_fields = array(
		"folder_name",
		"folder_memo"
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