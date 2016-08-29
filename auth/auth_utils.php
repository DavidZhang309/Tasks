<?php
include_once "constants.php";

function get_user_id($redirect = false)
{
	if (isset($_SESSION[SESSION_ID]))
	{
		return $_SESSION[SESSION_ID];
	}
	else if ($redirect)
	{
		header("location: /ch/login.php");
		exit();
	}
	else
	{
		return -1;
	}
}
function get_group_id()
{
	if (isset($_SESSION[SESSION_GROUP]))
	{
		return $_SESSION[SESSION_GROUP];
	}
	else
	{
		return GROUP_PUBLIC;
	}
}
?>