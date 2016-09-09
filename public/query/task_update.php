<?php
include_once __DIR__ . '/../../auth/php/auth_utils.php';
include_once __DIR__ . '/../../php/TaskDB.php';
include_once __DIR__ . '/../../php/utils.php';
session_start();

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

//TODO: make this into stored procedure
function create_task($db, $user_id, $project_id){
	//check if project exist/has authorization
	if (!can_edit($db, $user_id, $project_id)){
		abort("invalid_project");
	}

	//get task
	if (!isset($_POST["task"])){
		abort("no_task");
	}
	$task = $_POST["task"];

	//insert task
	$stmt = $db->prepare("
		INSERT INTO tbl_tasks (ProjectID, Task)
		VALUES (?, ?)
	");
	$stmt->bind_param("is", $project_id, $task);
	$stmt->execute();
	if ($stmt->insert_id == 0){
		abort("creation_error", $stmt->error);
	}
	else{
		echo json_encode(array("id" => $stmt->insert_id, "task" => $task));
	}
}

function update_task($db, $user_id, $project_id, $task_id){
	if (!can_edit($db, $user_id, $project_id)){
		abort("invalid_project");
	}
	if (isset($_POST["task"])) {

	}
	else if (isset($_POST["finished"])) {
		$finished = $_POST["finished"];
		$stmt = $db->prepare("
			UPDATE tbl_tasks 
			SET IsFinished=? 
			WHERE ProjectID=? AND TaskID=?
		");
		$stmt->bind_param("iii", $finished, $project_id, $task_id);
		$stmt->execute();
		echo json_encode(array());
	}
	else {
		abort("no_op");
	}
}

//check for prerequisites (user and project)
$user = get_user_id();
if ($user == -1) {
	abort("no_login");
}

if (!isset($_POST)) { //post request only
	abort("post_only");
}

if (isset($_POST["projectID"])){ 
	$project = $_POST["projectID"];
}
else {
	abort("no_project_id");
}

$db_connection = new TaskDB();
//intent logic
if (isset($_POST["taskID"])) {
	$task_id = $_POST["taskID"];
	if ($task_id == -1){ //task creation
		create_task($db_connection, $user, $project);
	}
	else{
		update_task($db_connection, $user, $project, $task_id);
	}
}
else{
	abort("no_task_id");
}