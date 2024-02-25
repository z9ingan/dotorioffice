<?
if(!defined('DOTORI_CONTROLLER')) exit;
class boardModel Extends Model{

	public $alias = 'BD';
	public $use_file = false;

	public $select_fields = array(
		"board_name",
		"board_memo",
		"use_comment",
		"use_tag"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->folderModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->folderModel->getTable()." AS ".$this->folderModel->getAlias();
		$query.= " ON ".$this->alias.".folder_idx = ".$this->folderModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}
}

?>