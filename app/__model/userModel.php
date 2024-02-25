<?
if(!defined('DOTORI_CONTROLLER')) exit;
class userModel Extends Model{

	private $accessor = array();

	public $alias = 'US';
	public $use_file = false;

	public $select_fields = array(
		"user_id",
		"user_password",
		"user_level",
		"user_name",
		"user_position",
		"user_tel",
		"user_fax",
		"user_direct",
		"user_mobile",
		"user_email",
		"user_zipcode",
		"user_address1",
		"user_address2",
		"user_bank",
		"user_bankaccount",
		"user_bankholder",
		"user_entertime",
		"user_leavetime",
		"user_memo",
		"user_photo");

	public function init(){

		$idx = $this->session->data('accessor_idx');

		if($idx){
			$accessor = $this->get($idx);
			if($accessor){
				$this->renew($accessor);
			}
		}
	}

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".* ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " WHERE 1 ";
		return $query;
	}	

	public function isLogged(){
		return $this->accessor ? true : false;
	}

	public function getAccessor(){

		if($this->isLogged()){

			$clean = $this->accessor;
			$clean['ipp'] = $this->helper->getIP(); // IP 추가
			$clean['device'] = $this->helper->getDevice();
			$clean['os'] = $this->helper->getOS();
			$clean['browser'] = $this->helper->getBrowser();

			return $clean;
		}else{
			return false;
		}
	}

	private function renew(array $accessor){

		$this->accessor = $accessor;

		$this->session->setData('accessor_idx', $accessor['idx']);

	}

	public function login($user_id, $password, $company_idx = null){

		$hashed_password = hash($this->getAlgorithm(), $this->getSalt().$password);
		$accessor = $this->get($user_id, 'user_id');
		if($accessor){
			if($accessor['user_password'] == $hashed_password && $accessor['user_level'] > 0 && $accessor['company_idx'] == $company_idx){
				$this->renew($accessor);
				return $accessor;
			}
		}

		return false;
	}

	public function logout(){

		$this->session->reset();

	}

	// 아이디 유무 체크
	public function isUserId($user_id){
		$user = $this->get($user_id, 'user_id');
		return $user ? true : false;
	}

}

?>