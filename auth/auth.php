<?php
include_once "authDB.php";
include_once "auth_utils.php";
include_once "constants.php";

session_start();
//input
$user = $_POST["user_input"];
$pass = $_POST["pass_input"];

$db_connection = new AuthDB();
$statement = $db_connection->prepare("SELECT UserID, GroupID, Username, Password FROM tblusers WHERE Username=?");
$statement->bind_param("s", $user);
$statement->execute();
$statement->bind_result($query_id, $query_group, $query_user, $query_password);
if ($statement->fetch())
{
	if ($user == $query_user){
        if ($pass == $query_password)
	{
		//login
		$_SESSION[SESSION_ID] = $query_id;
		$_SESSION[SESSION_GROUP] = $query_group;
		header("location: /ch/word_group.php"); 
	}
	else
    {
	    $_SESSION[SESSION_ERROR] = "Wrong Credentials"; 
	    header("location: login.php");
    }
        exit();
	}

}

$_SESSION[SESSION_ERROR] = "Account does not exist"; 
header("location: login.php");
?>

