<?
if(!defined('DOTORI_CONTROLLER')) exit;
class noteModel Extends Model{

	public $alias = 'NT';
	public $use_file = false;

	public $select_fields = array(
		"note_content",
		"note_viewtime"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->userModel->getFields().", ";
		$query.= " ".$this->userModel->getAlias()."N.user_name AS note_user_name ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias();
		$query.= " ON ".$this->alias.".user_idx = ".$this->userModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias()."N ";
		$query.= " ON ".$this->alias.".note_user_idx = ".$this->userModel->getAlias()."N.idx ";
		$query.= " WHERE 1 ";
		return $query;
	}

}

?>