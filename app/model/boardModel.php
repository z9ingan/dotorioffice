<?
if(!defined('DOTORI_CONTROLLER')) exit;
class boardModel Extends Model{

	public $alias = 'BD';
	public $use_file = false;

	public $select_fields = array(
		"is_notice",
		"board_name",
		"board_memo",
		"use_board",
		"use_header",
		"use_file",
		"use_comment",
		"use_tag"
	);

	// 기본 셀렉트문
	public function getSelect(){
		$query = " SELECT ".$this->alias.".*, ";
		$query.= " ".$this->folderModel->getFields()." ";
		$query.= " FROM ".$this->table." AS ".$this->alias;
		$query.= " LEFT JOIN ".$this->folderModel->getTable()." AS ".$this->folderModel->getAlias();
		$query.= " ON ".$this->alias.".folder_idx = ".$this->folderModel->getAlias().".idx ";
		$query.= " WHERE 1 ";
		return $query;
	}

	public function getNotice($company_idx){
		return $this->get(array('company_idx' => $company_idx, 'is_notice' => 1));
	}

	public function addBoard($params){
		$stmt = $this->db->stmt_init();
		$stmt->prepare("INSERT INTO ".$this->table." (time, company_idx, folder_idx, is_notice, board_name, board_memo, use_board, use_header, use_file, use_comment, use_tag) VALUES (UNIX_TIMESTAMP(),?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param('iissiiiii', ...$params);
		$result = $stmt->execute();
		$insert_id = $result ? $stmt->insert_id : false;
		$stmt->close();
		return $insert_id;
	}
}

?>