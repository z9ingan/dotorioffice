<?
if(!defined('DOTORI_CONTROLLER')) exit;
class noticefileModel Extends Model{

	public $alias = 'NTF';
	public $use_file = true;

	public $select_fields = array(
		"filename",
		"filesize",
		"filetype",
		"fakename",
		"fileext");

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->noticeModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->noticeModel->getTable()." AS ".$this->noticeModel->getAlias();
		$query.= " ON ".$this->alias.".notice_idx = ".$this->noticeModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}
}

?>