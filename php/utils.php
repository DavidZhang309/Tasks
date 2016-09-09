<?php
function abort($error_type, $error_msg = "")
{
	echo json_encode(array("error_type" => $error_type, "error_msg" => $error_msg));
	exit();
}

function get_post_data($key){
	if (isset($_POST[$key])){
		return $_POST[$key];
	}
	else{
		return false;
	}
}

?>