<?
if(!defined('DOTORI_CONTROLLER')) exit;
class aplineModel Extends Model{

	public $alias = 'AL';
	public $use_file = false;

	public $select_fields = array(
		"apline_time",
		"apline_type",
		"apline_result",
		"apline_name",
		"apline_position",
		"apline_viewtime"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->signModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->signModel->getTable()." AS ".$this->signModel->getAlias();
		$query.= " ON ".$this->alias.".sign_idx = ".$this->signModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}

}

?>