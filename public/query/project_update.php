<?php
include_once __DIR__ . '/../../auth/php/auth_utils.php';
include_once __DIR__ . '/../../php/TaskDB.php';
include_once __DIR__ . '/../../php/utils.php';
session_start();

function redirect() {
	header("location: ../index.php");
	exit();
}

//temp: this will be a db function
function can_edit($db, $user_id, $project_id){
	$stmt = $db->prepare("
		SELECT *
		FROM tbl_project_collaborators
		WHERE UserID=? AND ProjectID=?
	");
	$stmt->bind_param("ii", $user_id, $project_id);
	$stmt->execute();
	$has_project = $stmt->fetch();
	$stmt->close();
	return $has_project;
}

$user_id = get_user_id();

//POST only requests
if (!isset($_POST)){
	//abort("post_only");
	redirect();
}

$db_connection = new TaskDB();

$project = get_post_data("projectID");
if ($project === false || $project == -1) {
	//get project name
	$project_name = get_post_data("projectName");
	if ($project_name === false){
		//abort("invalid_input", "No project name was given.");
		redirect();
	}

	//create project
	$stmt = $db_connection->prepare("
		INSERT INTO tbl_projects (ProjectName)
		VALUES (?)
	");
	$stmt->bind_param("s", $project_name);
	$stmt->execute();
	$project_id = $stmt->insert_id;
	$stmt->close();

	//insert user as collaborator
	$stmt = $db_connection->prepare("
		INSERT INTO tbl_project_collaborators (ProjectID, UserID)
		VALUES (?, ?)
	");
	$stmt->bind_param("ii", $project_id, $user_id);
	$stmt->execute();

	redirect();
	// echo json_encode(array("id" => $project_id, "name" => $project_name));
	// exit();
}
else {
	//update project
	redirect();
	//abort("not_implemented");
}

?>