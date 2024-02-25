<?
if(!defined('DOTORI_CONTROLLER')) exit;
class noticeModel Extends Model{

	public $alias = 'NT';
	public $use_file = true;

	public $select_fields = array(
		"notice_title",
		"notice_memo");

	// 기본 셀렉트문
	public function getSelect(){

		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->companyModel->getFields().", ";
		$query.= " ".$this->userModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->companyModel->getTable()." AS ".$this->companyModel->getAlias();
		$query.= " ON ".$this->alias.".company_idx = ".$this->companyModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias();
		$query.= " ON ".$this->alias.".user_idx = ".$this->userModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}

}

?>