<?
if(!defined('DOTORI_CONTROLLER')) exit;
class deptModel Extends Model{

	public $alias = 'DP';
	public $use_file = false;

	public $select_fields = array(
		"dept_name",
		"dept_memo"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".* ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " WHERE 1 ";
		return $query;
	}

	public function getDepts($company_idx, $dept_idx){
		$depts = $this->gets(array('company_idx' => $company_idx, 'dept_idx' => $dept_idx),null,array('dept_order' => 'ASC'));
		if($depts){
			foreach ($depts as &$dept) {
				$dept['childs'] = $this->getDepts($company_idx, $dept['idx']);
			}
		}
		return $depts;
	}

	public function getMaxDeptOrder($company_idx, $dept_idx){
		
		$query = "SELECT MAX(dept_order) AS max_dept_order FROM ".$this->table." WHERE company_idx = ".$company_idx." AND dept_idx = ".$dept_idx;
		$result = $this->db->query($query);
		if($result){
			$data = $result->fetch_assoc();
			return $data['max_dept_order'];
		}
		return 0;
	}

	public function addDept($params){
		$stmt = $this->db->stmt_init();
		$stmt->prepare("INSERT INTO ".$this->table." (time, company_idx, dept_idx, dept_name, dept_memo, dept_order) VALUES (UNIX_TIMESTAMP(),?,?,?,?,?)");
		$stmt->bind_param('iissi', ...$params);
		$result = $stmt->execute();
		$insert_id = $result ? $stmt->insert_id : false;
		$stmt->close();
		return $insert_id;
	}

	public function editDeptName($idx, $dept_name){
		$stmt = $this->db->stmt_init();
		$stmt->prepare("UPDATE ".$this->table." SET dept_name=? WHERE idx=?");
		$stmt->bind_param('si', $dept_name, $idx);
		$result = $stmt->execute();
		$stmt->close();
		return $result ? true : false;
	}

	public function editDeptOrder($idx, $dept_idx, $dept_order){
		$stmt = $this->db->stmt_init();
		$stmt->prepare("UPDATE ".$this->table." SET dept_idx=?, dept_order=? WHERE idx=?");
		$stmt->bind_param('iii', $dept_idx, $dept_order, $idx);
		$result = $stmt->execute();
		$stmt->close();
		return $result ? true : false;
	}

	public function deleteDept($idx){
		$stmt = $this->db->stmt_init();
		$stmt->prepare("DELETE FROM ".$this->table." WHERE idx=?");
		$stmt->bind_param('i', $idx);
		$result = $stmt->execute();
		$stmt->close();
		return $result ? true : false;
	}

}

?>