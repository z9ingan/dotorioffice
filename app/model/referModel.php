<?
if(!defined('DOTORI_CONTROLLER')) exit;
class referModel Extends Model{

	public $alias = 'RF';
	public $use_file = false;

	public $select_fields = array(
		"refer_type",
		"refer_viewtime"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->deptModel->getFields().", ";
		$query.= " ".$this->userModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->deptModel->getTable()." AS ".$this->deptModel->getAlias();
		$query.= " ON ".$this->alias.".refer_dept_idx = ".$this->deptModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias();
		$query.= " ON ".$this->alias.".refer_user_idx = ".$this->userModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}

}

?>