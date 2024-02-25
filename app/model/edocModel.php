<?
if(!defined('DOTORI_CONTROLLER')) exit;
class edocModel Extends Model{

	public $alias = 'ED';
	public $use_file = false;

	public $select_fields = array(
		"edoc_category",
		"edoc_name",
		"edoc_memo",
		"edoc_use"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->userModel->getFields().", ";
		$query.= " ".$this->eformModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias();
		$query.= " ON ".$this->alias.".user_idx = ".$this->userModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->eformModel->getTable()." AS ".$this->eformModel->getAlias();
		$query.= " ON ".$this->alias.".eform_idx = ".$this->eformModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}
}

?>