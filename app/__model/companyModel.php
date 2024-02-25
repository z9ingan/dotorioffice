<?
if(!defined('DOTORI_CONTROLLER')) exit;
class companyModel Extends Model{

	public $alias = 'CO';
	public $use_file = true;

	public $select_fields = array(
		"company_name",
		"company_regcode",
		"company_president",
		"company_zipcode",
		"company_address1",
		"company_address2",
		"company_tel",
		"company_fax",
		"company_email",
		"company_homepage",
		"company_upt",
		"company_upj",
		"company_bank",
		"company_bankaccount",
		"company_bankholder",
		"company_logo1",
		"company_logo2",
		"company_slogan"
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