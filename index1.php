<?php
    $GLOBALS['url_auth'] = "http://www.inspibook.com/wam/admin/auth/index";    
    $GLOBALS['key_auth'] = "5051c4572b9bf8df0080e7c828a1b017"; 

    $GLOBALS['username'] = "asdi";
    $GLOBALS['password'] = "3^M094A@Y43*n~";

	$GLOBALS['key_client'] = "123"; 
    //-----------------------------------------	
	function postdata($url_auth,$key_server,$key_client)
	{
		$postdata = http_build_query(
		    array(
		        'key_server' => $key_server,
			    'key_client' => $key_client			        
		    )
		);

		$opts = array('http' =>
		    array(
		        'method'  => 'POST',
		        'header'  => 'Content-Type: application/x-www-form-urlencoded',
		        'content' => $postdata
		    )
		);

		$context  = stream_context_create($opts);

		$result = file_get_contents($url_auth, false, $context);

		return $result;
	}
	
    function request_key($data,$username,$password,$key_client)
	{		
		$auth = explode('-',$data['auth']);
		if(($auth[0] == $username)&&($auth[1] == $password))
		{
			return postdata($GLOBALS['url_auth'],$auth[2],$key_client);
		}
	}
    //--------------------------------------
    function raw_data($field)
    {
    	$data = file_get_contents('php://input');
    	$array_data = explode('&', $data);
    	 
    	foreach($array_data as $item) 
        { 
          $array_item = explode('=', $item);          
          if($array_item[0] == $field)
          	return $array_item[1];
        }
        return '';
    }
    //--------------------------------------    
	if ($_SERVER['REQUEST_METHOD'] == "GET") 
	{
	    if ($_GET['url'] == "auth") 
	    {
	    	$data = request_key(getallheaders(),$GLOBALS['username'],$GLOBALS['password'],$GLOBALS['key_client']);
			$auth = json_decode($data);
			if(($auth->auth_server == $GLOBALS['key_auth'])&&($auth->auth_client == sha1($GLOBALS['key_client'])))
				echo $_GET['id'];
	    }
	}
	else
	if ($_SERVER['REQUEST_METHOD'] == "POST") 
	{
	    if ($_GET['url'] == "auth") 
	    {
	    	$data = request_key(getallheaders(),$GLOBALS['username'],$GLOBALS['password'],$GLOBALS['key_client']);
			$auth = json_decode($data);
			if(($auth->auth_server == $GLOBALS['key_auth'])&&($auth->auth_client == sha1($GLOBALS['key_client'])))
			{
				echo $_POST['username'];
				echo $_POST['password'];
			}
	    }
	    else
	    if ($_GET['url'] == "raw") 
	    {
	    	$data = request_key(getallheaders(),$GLOBALS['username'],$GLOBALS['password'],$GLOBALS['key_client']);
			$auth = json_decode($data);
			if(($auth->auth_server == $GLOBALS['key_auth'])&&($auth->auth_client == sha1($GLOBALS['key_client'])))
			{
				$json = file_get_contents("php://input");	
				echo $json;					
			}
	    }
	}
	else
	if ($_SERVER['REQUEST_METHOD'] == "PUT") 
	{
	    if ($_GET['url'] == "auth") 
	    {
	    	$data = request_key(getallheaders(),$GLOBALS['username'],$GLOBALS['password'],$GLOBALS['key_client']);
			$auth = json_decode($data);
			if(($auth->auth_server == $GLOBALS['key_auth'])&&($auth->auth_client == sha1($GLOBALS['key_client'])))
			{				
				echo raw_data('username');
				echo raw_data('password');

			}
	    }
	}
	else
	if ($_SERVER['REQUEST_METHOD'] == "DELETE") 
	{
	    if ($_GET['url'] == "auth") 
	    {
	    	$data = request_key(getallheaders(),$GLOBALS['username'],$GLOBALS['password'],$GLOBALS['key_client']);
			$auth = json_decode($data);
			if(($auth->auth_server == $GLOBALS['key_auth'])&&($auth->auth_client == sha1($GLOBALS['key_client'])))
			{
				echo raw_data('id');
			}
	    }
	}
?>
