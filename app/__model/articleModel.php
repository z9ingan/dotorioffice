<?
if(!defined('DOTORI_CONTROLLER')) exit;
class articleModel Extends Model{

	public $alias = 'AR';
	public $use_file = false;

	public $select_fields = array(
		"article_title",
		"article_content"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->boardModel->getFields().", ";
		$query.= " ".$this->userModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->boardModel->getTable()." AS ".$this->boardModel->getAlias();
		$query.= " ON ".$this->alias.".board_idx = ".$this->boardModel->getAlias().".idx ";
		$query.= " LEFT JOIN ".$this->userModel->getTable()." AS ".$this->userModel->getAlias();
		$query.= " ON ".$this->alias.".user_idx = ".$this->userModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}
}

?>