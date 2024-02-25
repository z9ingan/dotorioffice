<?

abstract class Model{
	
	public $table;
	public $alias;
	public $select_fields;

	public $use_file = false;

	public $salt = 'dotori'; // 패스워드 암호화 salt key
	public $algo = 'sha1'; // 패스워드 암호화 함수
	public $thumb_width = 200; // 기본 썸네일 가로 높이
	public $thumb_height = 200; // 기본 썸네일 세로 높이
	public $file_dir;
	public $thumb_dir;

	public function __get($name){

		$Controller = Controller::getInstance();
		return $Controller->$name;

	}
	
	public function getTable(){
		return $this->table;
	}

	public function setTable($table){
		$this->table = $table;
	}

	public function getAlias(){
		return $this->alias;
	}

	public function setAlias($alias){
		$this->alias = $alias;
	}

	public function getSalt(){
		return $this->salt;
	}

	public function setSalt($salt){
		$this->salt = $salt;
	}

	public function getAlgorithm(){
		return $this->algo;
	}

	public function setAlgorithm($algorithm){
		$this->algo = $algo;
	}

	public function setUseFile($value){
		$this->use_file = $value;
	}

	public function getThumbWidth(){
		return $this->thumb_width;
	}

	public function setThumbWidth($width){
		$this->thumb_width = $width;
	}

	public function getThumbHeight(){
		return $this->thumb_height;
	}

	public function setThumbHeight($height){
		$this->thumb_height = $height;
	}

	public function getFileDir(){
		return $this->file_dir;
	}

	public function setFileDir($dir){
		$this->file_dir = $dir;
	}

	public function getThumbDir(){
		return $this->thumb_dir;
	}

	public function setThumbDir($dir){
		$this->thumb_dir = $dir;
	}

	// 기본 셀렉트문
	abstract public function getSelect(); // return query string

	// 조인용 쿼리
	public function getFields($alias = NULL){
		$query = array();
		if($this->select_fields){
			foreach($this->select_fields as $field){
				$query[] = ($alias ? $alias."." : ($this->alias ? $this->alias."." : "")).$field;
			}
		}
		return implode(', ', $query);
	}

	// 기본 1개 불러오기
	public function get($conditions, $type = 'idx', $index = false, $debug = false){
		$query = $this->getSelect();
		if(is_array($conditions)){
			foreach($conditions as $field => $value){
				if(is_array($value)){
					if(isset($value['between'])){
						$query.= " AND (".$this->alias.".".$field." BETWEEN ".$value['between'][0]." AND ".$value['between'][1].") ";
					}else if(isset($value['>='])){
						$query.= " AND ".$this->alias.".".$field." >= '".$value['>=']."' ";
					}else if(isset($value['<='])){
						$query.= " AND ".$this->alias.".".$field." <= '".$value['<=']."' ";
					}else if(isset($value['>'])){
						$query.= " AND ".$this->alias.".".$field." > '".$value['>']."' ";
					}else if(isset($value['<'])){
						$query.= " AND ".$this->alias.".".$field." < '".$value['<']."' ";
					}else if(isset($value['!='])){
						$query.= " AND ".$this->alias.".".$field." != '".$value['!=']."' ";
					}else if(isset($value['like'])){
						$query.= " AND ".$this->alias.".".$field." LIKE '%".$value['like']."%' ";
					}else if(is_int($field) && $value){
						$query.= " AND (".$value.") ";
					}else{
						$query.= " AND ".$this->alias.".".$field." IN ('".implode("','", $value)."') ";
					}
				}else if($value === true){
					$query.= " AND ".$this->alias.".".$field." IS NOT NULL ";
				}else if($value === false){
					$query.= " AND ".$this->alias.".".$field." IS NULL ";
				}else{
					$query.= " AND ".$this->alias.".".$field." = '".$value."' ";
				}
			}
		}else{
			$query.= " AND ".$this->alias.".".$type." = '".$conditions."' ";
		}

		if($debug){
			echo $query;
			exit;
		}

		$result = $this->db->query($query);
		if($result){
			$data = $result->fetch_assoc();
			if($data){
				if($this->use_file){
					if(@$data['fakename']){
						$data['file'] = $this->file_dir.'/'.$data['fakename'].'.'.$data['fileext'];
						if(file_exists($this->thumb_dir.'/'.$data['fakename'].'.png')){
							$data['thumb'] = $this->thumb_dir.'/'.$data['fakename'].'.png';
						}
					}else{
						$data['file'] = false;
						$data['thumb'] = false;
					}
				}

				if($index){
					return array($data['idx'] => $data);
				}else{
					return $data;
				}
			}
		}
		return false;
	}

	// 기본 여러개 불러오기
	/* $conditions = array(
		'idx' => array(1,2,3),
		'time' => array('between' => array(time, time)),
		'value' => array('>=' => value));*/
	public function gets($conditions = NULL, $groupby = NULL, $orderby = NULL, $limit = NULL, $offset = NULL, $index = false, $debug = false){
		$query = $this->getSelect();
		if($conditions){
			if(is_array($conditions)){
				foreach($conditions as $field => $value){
					if(is_array($value)){
						if(isset($value['between'])){
							$query.= " AND (".$this->alias.".".$field." BETWEEN ".$value['between'][0]." AND ".$value['between'][1].") ";
						}else if(isset($value['>='])){
							$query.= " AND ".$this->alias.".".$field." >= '".$value['>=']."' ";
						}else if(isset($value['<='])){
							$query.= " AND ".$this->alias.".".$field." <= '".$value['<=']."' ";
						}else if(isset($value['>'])){
							$query.= " AND ".$this->alias.".".$field." > '".$value['>']."' ";
						}else if(isset($value['<'])){
							$query.= " AND ".$this->alias.".".$field." < '".$value['<']."' ";
						}else if(isset($value['!='])){
							$query.= " AND ".$this->alias.".".$field." != '".$value['!=']."' ";
						}else if(isset($value['like'])){
							$query.= " AND ".$this->alias.".".$field." LIKE '%".$value['like']."%' ";
						}else{
							$query.= " AND ".$this->alias.".".$field." IN ('".implode("','", $value)."') ";
						}
					}else if($value === true){
						$query.= " AND ".$this->alias.".".$field." IS NOT NULL ";
					}else if($value === false){
						$query.= " AND ".$this->alias.".".$field." IS NULL ";
					}else if(is_int($field) && $value){
						$query.= " AND (".$value.") ";
					}else{
						$query.= " AND ".$this->alias.".".$field." = '".$value."' ";
					}
				}
			}else{
				$query.= $condition;
			}
		}
		if($groupby){
			if(is_array($groupby)){ // 배열이면 개별적으로 넣기
				$query.= " GROUP BY ";
				foreach($groupby as $field) $query_groupby[] = " ".$this->alias.".".$field." ";
				$query.= implode(',', $query_groupby);
			}else{ // 아니면 바로 넣기
				$query.= $groupby;
			}
		}
		if($orderby){
			if(is_array($orderby)){ // 배열이면 개별적으로 넣기
				$query.= " ORDER BY ";
				foreach($orderby as $field => $sort) $query_orderby[] = " ".$this->alias.".".$field." ".$sort." ";
				$query.= implode(',', $query_orderby);
			}else{ // 아니면 바로 넣기
				$query.= $orderby;
			}
		}
		if($offset && $limit){
			$query.= " LIMIT ".$offset.", ".$limit." ";
		}else if($limit){
			$query.= " LIMIT ".$limit." ";
		}

		if($debug){
			echo $query;
			exit;
		}

		$result = $this->db->query($query);

		if($result){

			$datas = array();

			while($data = $result->fetch_assoc()){
				
				if($this->use_file){
					if($data['fakename']){
						$data['file'] = $this->file_dir.'/'.$data['fakename'].'.'.$data['fileext'];
						if(file_exists($this->thumb_dir.'/'.$data['fakename'].'.png')){
							$data['thumb'] = $this->thumb_dir.'/'.$data['fakename'].'.png';
						}
					}else{
						$data['file'] = false;
						$data['thumb'] = false;
					}
				}
				
				if($index){
					$datas[$data['idx']] = $data;
				}else{
					$datas[] = $data;
				}
			}
			return $datas;
		}else{
			return false;
		}
	}

	public function insert(array $datas = NULL, $debug = false){
		$query = " INSERT INTO ".$this->getTable()." ";
		$fields[] = "time";
		$values[] = " UNIX_TIMESTAMP() ";
		if($datas){
			foreach($datas as $field => $value){
				if(str_contains($field, 'password')){
					$fields[] = $field;
					$values[] = "'".hash($this->getAlgorithm(), $this->getSalt().$value)."'";
				}else if($value === null){
					$fields[] = $field;
					$values[] = "NULL";
				}else{
					$fields[] = $field;
					$values[] = "'".$value."'";
				}
			}
		}
		$query.= " ( ".implode(', ', $fields)." ) ";
		$query.= " VALUES ( ".implode(', ', $values)." ) ";

		if($debug){
			echo $query;
			exit;
		}

		$result = $this->db->query($query);
		return $result ? $this->db->insert_id : false;
	}
	
	public function update($idx, array $datas = NULL, $debug = false){
		if(!$datas) return false;
		$query = " UPDATE ".$this->getTable()." SET ";
		foreach($datas as $field => $value){
			if(str_contains($field, 'password')){
				$values[] = " ".$field." = '".hash($this->getAlgorithm(), $this->getSalt().$value)."' ";
			}else if($value === null){
				$values[] = " ".$field." = NULL ";
			}else{
				$values[] = " ".$field." = '".$this->db->escape($value)."' ";
			}
		}
		$query.= " ".implode(', ', $values)." ";
		$query.= " WHERE idx = '".$idx."' ";
		
		if($debug){
			echo $query;
			exit;
		}

		$result = $this->db->query($query);

		return $result;
	}

	public function delete($idx){
		$get = $this->get($idx);
		if(!$get) return false;

		$query = " DELETE FROM ".$this->getTable()." WHERE idx = '".$idx."' ";
		$result = $this->db->query($query);

		// 파일도 삭제
		if($result && $this->use_file){
			if(isset($get['file'])) @unlink($get['file']);
			if(isset($get['thumb'])) @unlink($get['thumb']);
		}

		return $result;
	}

	public function upload($idx, array $file, $thumb_type = 'crop'){
		
		// 가짜이름
		$fakename = str_pad($idx, 10, '0', STR_PAD_LEFT).substr(sha1(uniqid(mt_rand(),TRUE)), 0, 30);

		// 확장자
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

		// 디렉토리 없으면 생성
		if(!is_dir($this->file_dir)) mkdir($this->file_dir);
		if(!is_dir($this->thumb_dir)) mkdir($this->thumb_dir);

		// 파일타입이 이미지이면 썸네일을 생성
		if(in_array($file['type'], array('image/png', 'image/x-png', 'image/jpeg' ,'image/gif'))){
			// 썸네일 만들기
			$this->helper->makeThumb($file, $this->thumb_dir.'/'.$fakename.'.png', $thumb_type, $this->thumb_width, $this->thumb_height);
		}

		// 본파일 옮기기
		$result = move_uploaded_file($file['tmp_name'], $this->file_dir.'/'.$fakename.'.'.$ext);
		if(!$result){
			$result = copy($file['tmp_name'], $this->file_dir.'/'.$fakename.'.'.$ext);
		}
		if(!$result) return false;

		$update_datas = array(
			'filename' => $file['name'],
			'filesize' => $file['size'],
			'filetype' => $file['type'],
			'fakename' => $fakename,
			'fileext' => $ext);

		$result = $this->update($idx, $update_datas);
		return $result ? true : false;
	}

}

?>