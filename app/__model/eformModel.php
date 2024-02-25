<?
if(!defined('DOTORI_CONTROLLER')) exit;
class eformModel Extends Model{

	public $alias = 'EF';
	public $use_file = false;

	public $select_fields = array(
		"eform_html",
		"eform_image"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->userModel->getFields().", ";
		$query.= " ".$this->edocModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias();
		$query.= " ON ".$this->alias.".user_idx = ".$this->userModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->edocModel->getTable()." AS ".$this->edocModel->getAlias();
		$query.= " ON ".$this->alias.".edoc_idx = ".$this->edocModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}
}

?>