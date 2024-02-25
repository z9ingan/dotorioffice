<?
if(!defined('DOTORI_CONTROLLER')) exit;
class attendanceModel Extends Model{

	public $alias = 'AT';
	public $use_file = false;

	public $select_fields = array(
		"attendance_type",
		"attendance_device",
		"attendance_ipp",
		"attendance_memo"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".* ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " WHERE 1 ";
		return $query;
	}

}

?>