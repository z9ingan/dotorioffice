<?
if(!defined('DOTORI_CONTROLLER')) exit;
class approvalModel Extends Model{

	public $alias = 'AP';
	public $use_file = false;

	public $select_fields = array(
		"approval_recipient",
		"approval_security",
		"approval_status",
		"approval_no",
		"approval_title",
		"approval_content"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->userModel->getFields().", ";
		$query.= " ".$this->edocModel->getFields().", ";
		$query.= " ".$this->userModel->getAlias()."N.user_name AS now_user_name, ";
		$query.= " ".$this->userModel->getAlias()."N.user_position AS now_user_position ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias();
		$query.= " ON ".$this->alias.".user_idx = ".$this->userModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->edocModel->getTable()." AS ".$this->edocModel->getAlias();
		$query.= " ON ".$this->alias.".edoc_idx = ".$this->edocModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias()."N ";
		$query.= " ON ".$this->alias.".now_user_idx = ".$this->userModel->getAlias()."N.idx ";
		$query.= " WHERE 1 ";
		return $query;
	}
}

?>