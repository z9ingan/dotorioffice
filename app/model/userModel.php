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
		"user_message",
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
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->deptModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->deptModel->getTable()." AS ".$this->deptModel->getAlias();
		$query.= " ON ".$this->alias.".dept_idx = ".$this->deptModel->getAlias().".idx ";
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

	public function getCountDeptUsers($company_idx){
		$query = "SELECT COUNT(idx) as count_dept_user, dept_idx FROM ".$this->table." WHERE company_idx = ".$company_idx." GROUP BY dept_idx";
		$result = $this->db->query($query);
		if($result){
			$datas = array();
			while($data = $result->fetch_assoc()){
				$datas[$data['dept_idx']] = $data['count_dept_user'];
			}
		}
		return $result ? $datas : false;
	}

	public function addUser($params){

		$params[2] = hash($this->getAlgorithm(), $this->getSalt().$params[2]);
		$stmt = $this->db->stmt_init();
		$stmt->prepare("INSERT INTO ".$this->table." (time, company_idx, user_id, user_password, dept_idx, user_level, user_name, user_position, user_comcode, user_regcode, user_sex, user_tel, user_direct, user_mobile, user_email, user_zipcode, user_address1, user_address2, user_x, user_y, user_bank, user_bankaccount, user_bankholder, user_entertime, user_leavetime, user_memo, user_photo) VALUES (UNIX_TIMESTAMP(),?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param('issiissssssssssssddsssiiss', ...$params);
		$result = $stmt->execute();
		$insert_id = $result ? $stmt->insert_id : false;
		$stmt->close();
		return $insert_id;
	}

	public function editUser($idx, $params){
		$stmt = $this->db->stmt_init();
		$stmt->prepare("UPDATE ".$this->table." SET user_id=?, dept_idx=?, user_level=?, user_name=?, user_position=?, user_comcode=?, user_regcode=?, user_sex=?, user_tel=?, user_direct=?, user_mobile=?, user_email=?, user_zipcode=?, user_address1=?, user_address2=?, user_x=?, user_y=?, user_bank=?, user_bankaccount=?, user_bankholder=?, user_entertime=?, user_leavetime=?, user_memo=?, user_photo=? WHERE idx = ".$idx);
		$stmt->bind_param('siissssssssssssddsssiiss', ...$params);
		$result = $stmt->execute();
		$stmt->close();
		return $result ? true : false;
	}

	public function editUserPassword($idx, $password){
		$stmt = $this->db->stmt_init();
		$stmt->prepare("UPDATE ".$this->table." SET user_password=? WHERE idx=?");
		$hashed_password = hash($this->getAlgorithm(), $this->getSalt().$password);
		$stmt->bind_param('si', $hashed_password, $idx);
		$result = $stmt->execute();
		$stmt->close();
		return $result ? true : false;
	}

}

?>