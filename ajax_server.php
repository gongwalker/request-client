<?php
	error_reporting(0);
	date_default_timezone_set('Asia/Shanghai');

	# 进行请求
	$url = trim($_POST['url']);
	$yuan = trim($_POST['yuan']);
	$request_type= strtoupper($_POST['request']);
	$referer = trim(isset($_POST['referer'])?$_POST['referer']:'');
	$cookie = trim(isset($_POST['cookie'])?$_POST['cookie']:'');
	$header = trim(isset($_POST['header'])?$_POST['header']:'');
	request($url,$yuan,$request_type,$referer,$cookie,$header);


	# 以下为功能函数调用
	# post提交
	function post($post_data,$post_url,$referer='',$cookie='',$header=''){
		$post_data=http_build_query($post_data);//多维数组最好开启,一维数据可以注释掉
		# 添加header头和referer
		$headers = [];
		if(!empty($header)){
			$yuan = explode(";",$header);
			foreach($yuan as $row){
				list($key,$value) = explode('=',$row,2);
				$headers[] = trim($key).': '.trim($value);
			}

		}
		if(!empty($referer)){
			$headers[] = 'REFERER: '.$referer;
		}

		$curl = curl_init();

		# 传递自定义header与referer
		if(!empty($headers)){
			curl_setopt($curl, CURLOPT_HTTPHEADER  , $headers);
		}
		# 传递cookie
		if(!empty($cookie)){
			curl_setopt($curl,CURLOPT_COOKIE,$cookie);
		}
		curl_setopt($curl, CURLOPT_URL, $post_url);
		curl_setopt($curl, CURLOPT_POST, 1 );
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl,CURLOPT_USERAGENT,"Mozilla/4.0");
		$result = curl_exec($curl);
		$error = curl_error($curl);
		return $error ? $error : $result;
	}


	# get请求
    function get($get_data,$get_url,$referer='',$cookie='',$header=''){
		$post_data=http_build_query($get_url);//多维数组最好开启,一维数据可以注释掉
		# 添加header头和referer
		$headers = [];
		if(!empty($header)){
			$yuan = explode(";",$header);
			foreach($yuan as $row){
				list($key,$value) = explode('=',$row,2);
				$headers[] = trim($key).': '.trim($value);
			}

		}
		if(!empty($referer)){
			$headers[] = 'REFERER: '.$referer;
		}


		if(!empty($get_data)){
			$get_data = http_build_query($get_data);
			$get_url .= '?'.$get_data;
		}


		//var_dump($get_data);

		$curl = curl_init();

		# 传递自定义header与referer
		if(!empty($headers)){
			curl_setopt($curl, CURLOPT_HTTPHEADER  , $headers);
		}
		# 传递cookie
		if(!empty($cookie)){
			curl_setopt($curl,CURLOPT_COOKIE,$cookie);
		}
		curl_setopt($curl, CURLOPT_URL, $get_url);
		curl_setopt($curl, CURLOPT_POST, 1 );
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl,CURLOPT_USERAGENT,"Mozilla/4.0");
		$result = curl_exec($curl);
		$error = curl_error($curl);
		return $error ? $error : $result;
    }


	function request($url,$yuan,$request_type,$referer='',$cookie='',$header=''){
		$yuan = explode("\n",$yuan);
		$request_data = [];
		foreach($yuan as $row){
			list($key,$value) = explode('=',$row,2);
			$request_data[trim($key)] = trim($value);
		}

		if($request_type == 'POST')
		{
			$re = post($request_data,$url,$referer,$cookie,$header);
		}
		else if(($request_type == 'GET'))
		{
			$re = get($request_data,$url,$referer,$cookie,$header);
		}
		//echo $url;
		//var_dump($request_data);
		if(is_not_json($re)){
			echo $re;
		}else{
			$arr = json_decode($re,true);
			echo jsonFormat($arr);
		}
	}

	/** Json数据格式化
	 * @param  Mixed  $data   数据
	 * @param  String $indent 缩进字符，默认4个空格
	 * @return JSON
	 */
	function jsonFormat($data, $indent='&nbsp;&nbsp;&nbsp;&nbsp;    '){

		// 对数组中每个元素递归进行urlencode操作，保护中文字符
		array_walk_recursive($data, 'jsonFormatProtect');

		// json encode
		$data = json_encode($data);

		// 将urlencode的内容进行urldecode
		$data = urldecode($data);

		// 缩进处理
		$ret = '';
		$pos = 0;
		$length = strlen($data);
		$indent = isset($indent)? $indent : '    ';
		$newline = "\n<br><br>";
		$prevchar = '';
		$outofquotes = true;

		for($i=0; $i<=$length; $i++){

			$char = substr($data, $i, 1);

			if($char=='"' && $prevchar!='\\'){
				$outofquotes = !$outofquotes;
			}elseif(($char=='}' || $char==']') && $outofquotes){
				$ret .= $newline;
				$pos --;
				for($j=0; $j<$pos; $j++){
					$ret .= $indent;
				}
			}

			$ret .= $char;

			if(($char==',' || $char=='{' || $char=='[') && $outofquotes){
				$ret .= $newline;
				if($char=='{' || $char=='['){
					$pos ++;
				}

				for($j=0; $j<$pos; $j++){
					$ret .= $indent;
				}
			}

			$prevchar = $char;
		}
		return $ret;
	}

	# 将数组元素进行urlencode
	function jsonFormatProtect(&$val){
		if($val!==true && $val!==false && $val!==null){
			$val = urlencode($val);
		}
	}

	# 判断不是json格式
	function is_not_json($str){
		return is_null(json_decode($str));
	}