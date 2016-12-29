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

function create_task($db, $user_id, $project_id, $data){
	//check if project exist/has authorization
	if (!can_edit($db, $user_id, $project_id)){
		abort("invalid_project");
	}

	//get task name
	if (!isset($data['task_name'])){
		abort("no_task_name");
	}
	$task_name = $data['task_name'];

	//insert task
	$stmt = $db->prepare("
		INSERT INTO tbl_tasks (ProjectID, Task)
		VALUES (?, ?)
	");
	$stmt->bind_param("is", $project_id, $task_name);
	$stmt->execute();
	if ($stmt->insert_id == 0){
		abort("creation_error", $stmt->error);
	}
	else{
		echo json_encode(array("id" => $stmt->insert_id, "task" => $task_name));
	}
}

function update_task($db, $user_id, $project_id, $task_id, $data){
	//check if project exist/has authorization
	if (!can_edit($db, $user_id, $project_id)){
		abort("invalid_project");
	}

	if (isset($data["finished"])) { //update finish status
		$finished = $data["finished"];
		$stmt = $db->prepare("
			UPDATE tbl_tasks 
			SET IsFinished=? 
			WHERE ProjectID=? AND TaskID=?
		");
		$stmt->bind_param("iii", $finished, $project_id, $task_id);
		$stmt->execute();
	}

	echo json_encode(array());
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

$changes = $_POST['changes'];
switch ($changes['action']) {
	case 'create':
		create_task($db_connection, $user, $project, $changes);
		break;
	case 'update':
		if (isset($_POST['taskID'])) {
			update_task($db_connection, $user, $project, $_POST['taskID'], $changes);	
		} else {
			abort('no_task_id');
		}
		break;
	default:
		abort('invalid_action');
		break;
}