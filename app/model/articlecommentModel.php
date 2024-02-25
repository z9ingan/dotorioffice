<?
if(!defined('DOTORI_CONTROLLER')) exit;
class articlecommentModel Extends Model{

	public $alias = 'ARC';
	public $use_file = false;

	public $select_fields = array(
		"articlecomment_content"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->userModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias();
		$query.= " ON ".$this->alias.".user_idx = ".$this->userModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}
}

?>