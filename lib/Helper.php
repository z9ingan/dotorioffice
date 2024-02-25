<?

class helper{
	
	public function __get($name){

		$Controller = Controller::getInstance();
		return $Controller->$name;

	}

	// CURL POST
	public static function curl_post($url, array $post = NULL, array $options = array()){
		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => $url,
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 4,
			CURLOPT_POSTFIELDS => http_build_query($post)
		);

		$ch = curl_init();
		curl_setopt_array($ch, ($options + $defaults));
		if( ! $result = curl_exec($ch))
		{
			trigger_error(curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}

	// CURL GET
	public static function curl_get($url, array $get = NULL, array $options = array())
	{   
		$defaults = array(
			CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($get),
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_TIMEOUT => 4
		);
	   
		$ch = curl_init();
		curl_setopt_array($ch, ($options + $defaults));
		if( ! $result = curl_exec($ch))
		{
			trigger_error(curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}

	// 재배열 함수 ($_FILE 배열 복수일 경우 다시 배열해주는 함수)
	public static function re_array_files($file)
	{
		$file_ary = array();
		$file_count = count($file['name']);
		$file_key = array_keys($file);
		
		for($i=0;$i<$file_count;$i++)
		{
			foreach($file_key as $val)
			{
				$file_ary[$i][$val] = $file[$val][$i];
			}
		}
		return $file_ary;
	}

	public static function getDevice(){
		return 'PC';
		/*$mobile_detect = new Mobile_Detect;
		if($mobile_detect->isMobile())		return 'MOBILE';
		else if($mobile_detect->isTablet())	return 'TABLET';
		else								return 'PC';*/
	}

	public static function getIP(){
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))				$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))		$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))				$ipaddress = getenv('REMOTE_ADDR');
		else										$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public static function getUserAgent(){

		return isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : NULL;

	}

	public static function getReferer(){
		
		return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;

	}

	public static function getOS($UA = NULL){

		$user_agent = $UA ? $UA : $_SERVER["HTTP_USER_AGENT"];

		if(preg_match('/android/i', $user_agent))					$os = 'Android';
		else if(preg_match('/macintosh|mac os x/i', $user_agent))	$os = 'Apple';
		else if (preg_match('/windows|win32/i', $user_agent))		$os = 'Windows';
		else if (preg_match('/linux/i', $user_agent))				$os = 'Linux';
		else														$os = 'Other';
		return $os;
	}

	public static function getBrowser($UA = NULL){

		$user_agent = $UA ? $UA : $_SERVER["HTTP_USER_AGENT"];

        $t = strtolower($user_agent);
        $t = " " . $t;

        // Humans / Regular Users     
        if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return 'Opera'				;
        elseif (strpos($t, 'kakaotalk-scrap' )                     ) return '[Bot] Kakaotalk'	;
        elseif (strpos($t, 'kakaotalk' )                           ) return 'Kakaotalk-App'		;
        elseif (strpos($t, 'naver(inapp;')                         ) return 'Naver-App'			;
        elseif (strpos($t, 'daumapps'  )                           ) return 'Daum-App'			;
        elseif (strpos($t, 'edge'      ) || strpos($t, 'edg')      ) return 'Edge'				; // edg는 크롬엔진 edge
        elseif (strpos($t, 'whale'     )                           ) return 'Whale'				;
        elseif (strpos($t, 'samsungbrowser' )                      ) return 'Samsung'			;
        elseif (strpos($t, 'chrome'    )                           ) return 'Chrome'			;
        elseif (strpos($t, 'safari'    )                           ) return 'Safari'			;
        elseif (strpos($t, 'firefox'   )                           ) return 'Firefox'			;
        elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return 'Internet Explorer'	;

        // Search Engines 
        elseif (strpos($t, 'adsbot-naver')                         ) return '[Bot] Naver'		;
        elseif (strpos($t, 'google'    )                           ) return '[Bot] Googlebot'	;
        elseif (strpos($t, 'bing'      )                           ) return '[Bot] Bingbot'		;
        elseif (strpos($t, 'slurp'     )                           ) return '[Bot] Yahoo! Slurp';
        elseif (strpos($t, 'duckduckgo')                           ) return '[Bot] DuckDuckBot'	;
        elseif (strpos($t, 'baidu'     )                           ) return '[Bot] Baidu'		;
        elseif (strpos($t, 'yandex'    )                           ) return '[Bot] Yandex'		;
        elseif (strpos($t, 'sogou'     )                           ) return '[Bot] Sogou'		;
        elseif (strpos($t, 'exabot'    )                           ) return '[Bot] Exabot'		;
        elseif (strpos($t, 'msn'       )                           ) return '[Bot] MSN'			;

        // Common Tools and Bots
        elseif (strpos($t, 'mj12bot'   )                           ) return '[Bot] Majestic'	;
        elseif (strpos($t, 'ahrefs'    )                           ) return '[Bot] Ahrefs'		;
        elseif (strpos($t, 'semrush'   )                           ) return '[Bot] SEMRush'		;
        elseif (strpos($t, 'rogerbot'  ) || strpos($t, 'dotbot')   ) return '[Bot] Moz or OpenSiteExplorer';
        elseif (strpos($t, 'frog'      ) || strpos($t, 'screaming')) return '[Bot] Screaming Frog';
       
        // Miscellaneous
        elseif (strpos($t, 'facebook'  )                           ) return '[Bot] Facebook'	;
        elseif (strpos($t, 'pinterest' )                           ) return '[Bot] Pinterest'	;
       
        // Check for strings commonly used in bot user agents  
        elseif (strpos($t, 'crawler' ) || strpos($t, 'api'    ) ||
                strpos($t, 'spider'  ) || strpos($t, 'http'   ) ||
                strpos($t, 'bot'     ) || strpos($t, 'archive') ||
                strpos($t, 'info'    ) || strpos($t, 'data'   )    ) return '[Bot] Other'		;
       
        return 'Other (Unknown)';
	}

	public static function getDirSize($dir){
		$count_size = 0;
		$count = 0;
		if(is_dir($dir)){
		$dir_array = scandir($dir);
			foreach($dir_array as $key=>$filename){
				if($filename!=".." && $filename!="."){
					if(is_dir($dir."/".$filename)){
						$new_foldersize = self::getDirSize($dir."/".$filename);
						$count_size = $count_size+ $new_foldersize;
					}else if(is_file($dir."/".$filename)){
						$count_size = $count_size + filesize($dir."/".$filename);
						$count++;
					}
				}
			}
			return $count_size;
		}
		return 0;
	}

	public static function convertSize($size, $point = 1){
		$base = log($size) / log(1024);
		$suffix = array("B", "KB", "MB", "GB", "TB");
		$f_base = floor($base);
		return round(pow(1024, $base - floor($base)), $point) . $suffix[$f_base];
	}

	public static function makeThumb(array $file, $output_file = null, $type = 'crop', $width, $height){

		// 이미지가 아니면 false
		if(!in_array($file['type'], array('image/png', 'image/x-png', 'image/jpeg' ,'image/gif'))) return false;

		// 원본이미지 불러오기
		$ori_picture = imagecreatefromstring(file_get_contents($file['tmp_name']));
		$ori_width = imagesx($ori_picture);
		$ori_height = imagesy($ori_picture);

		// 썸네일 준비
		$virtual_width = $width;
		$virtual_height = $height;
		$virtual_picture = imagecreatetruecolor($virtual_width, $virtual_height);

		// 투명화
		// enable alpha blending on the destination image. 
		imagealphablending($virtual_picture, true); 
		// Allocate a transparent color and fill the new image with it. 
		// Without this the image will have a black background instead of being transparent. 
		$transparent = imagecolorallocatealpha( $virtual_picture, 0, 0, 0, 127 ); 
		imagefill( $virtual_picture, 0, 0, $transparent );

		// 중앙 좌표 찾기
		$center_point_x = $ori_width / 2;
		$center_point_y = $ori_height / 2;

		// 원본과 썸네일의 비율계산
		$width_per = $ori_width / $virtual_width;
		$height_per = $ori_height / $virtual_height;

		// 타입에 맞춰 이미지 크기 결정하기
		if(strtolower($type) == 'crop'){
			// 낮은 비율에 맞춰 시작좌표를 찾는다.
			if($width_per > $height_per){
				$offset_x = $center_point_x - (($virtual_width * $height_per) / 2);
				$offset_y = $center_point_y - (($virtual_height * $height_per) / 2); // 0;
				$cut_width = $virtual_width * $height_per;
				$cut_height = $virtual_height * $height_per;
			}else{
				$offset_x = $center_point_x - (($virtual_width * $width_per) / 2); // 0;
				$offset_y = $center_point_y - (($virtual_height * $width_per) / 2);
				$cut_width = $virtual_width * $width_per;
				$cut_height = $virtual_height * $width_per;
			}
			imagecopyresampled($virtual_picture,$ori_picture,0,0,$offset_x,$offset_y, $virtual_width, $virtual_height, $cut_width, $cut_height);
		}else if(strtolower($type) == 'fit'){
			// 낮은 비율에 맞춰 시작좌표를 찾는다.
			if($width_per > $height_per){
				$offset_x = 0;
				$offset_y = ($virtual_height - ($ori_height / $width_per)) / 2;
				$dest_width = $virtual_width;
				$dest_height = $ori_height / $width_per;
			}else{
				$offset_x = ($virtual_width - ($ori_width / $height_per)) / 2;
				$offset_y = 0;
				$dest_width = $ori_width / $height_per;
				$dest_height = $virtual_height;
			}
			imagecopyresampled($virtual_picture,$ori_picture,$offset_x,$offset_y,0,0,$dest_width, $dest_height, $ori_width, $ori_height);
		}
		imagealphablending($virtual_picture, false); 
		// save the alpha 
		imagesavealpha($virtual_picture,true);
		@unlink($newfile);
		if($output_file){
			$result = imagepng($virtual_picture, $output_file);
		}else{
			$result = base64_encode($virtual_picture);
		}
		imagedestroy($virtual_picture);

		return $result;
	}
	
	public static function rotateImage($file, $mime_type, $rotate = 90){

		if(!file_exists($file)) return false;
		if(!in_array($mime_type, array('image/png', 'image/x-png', 'image/jpeg' ,'image/gif'))) return false;

		if($mime_type == 'image/png'){
			$picture = imagecreatefrompng($file);
			$rotate = imagerotate($picture, $rotate, imagecolorallocatealpha($picture, 0, 0, 0, 127));
			imagealphablending($rotate, false);
			imagesavealpha($rotate,true);
			imagepng($rotate, $file);
		}else if($mime_type == 'image/gif'){
			$picture = imagecreatefromgif($file);
			$rotate = imagerotate($picture, $rotate, 0);
			imagegif($rotate, $file);
		}else if($mime_type == 'image/jpeg'){
			$picture = imagecreatefromjpeg($file);
			$rotate = imagerotate($picture, $rotate, 0);
			imagejpeg($rotate, $file);
		}
		imagedestroy($picture);
		imagedestroy($rotate);
		return true;
	}

}

?>