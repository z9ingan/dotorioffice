<?
if(!defined('DOTORI_CONTROLLER')) exit;
class signModel Extends Model{

	public $alias = 'SG';
	public $use_file = false;

	public $select_fields = array(
		"sign_image"
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