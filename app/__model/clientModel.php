<?
if(!defined('DOTORI_CONTROLLER')) exit;
class clientModel Extends Model{

	public $alias = 'CL';
	public $use_file = true;

	public $select_fields = array(
		"client_name",
		"client_regcode",
		"client_president",
		"client_zipcode",
		"client_address1",
		"client_address2",
		"client_tel",
		"client_fax",
		"client_email",
		"client_homepage",
		"client_upt",
		"client_upj",
		"client_bank",
		"client_bankaccount",
		"client_bankholder",
		"client_memo"
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