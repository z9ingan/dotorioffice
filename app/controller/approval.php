<?

if(!defined('DOTORI_CONTROLLER')) exit;
include_once "dotoriofficeController.php"; // parent load
class approval extends dotoriofficeController{

	public function __construct(){
		parent::__construct();
		if($this->accessor['user_level'] < 2) $this->error('권한이 없습니다.');
	}

	public function index(){
		$this->load->view('_header.php', $this->clean);
		$this->load->view(DOTORI_CONTROLLER.'/index.php', $this->clean);
		$this->load->view('_footer.php', $this->clean);
	}

}

?>