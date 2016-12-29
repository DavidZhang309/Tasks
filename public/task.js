function clearQuickTaskModal() {
	var $modal = $("#quick-task");
	$modal.find(".task-name").val("");
	$modal.find(".task-body").html("");
}

function updateCompletedTask($project) {
	if ($project.find(".project-tasks-completed").children().length == 0) {
		$project.find(".project-tasks-completed-show").addClass("hidden");	
	} 
	else {
		$project.find(".project-tasks-completed-show").removeClass("hidden");
	}
}

function openConfirmation($modal, confirmType, confirmMsg) {
	$modal.find(".modal-body").html(confirmMsg);
	$modal
		.attr("data-confirm", confirmType)
		.modal("show");
}

// Queries
function displayError(error_type, error_msg) {
	alert("Error: " + error_type + ": " + error_msg);
}

function ajaxErrorHandle(error) {
	displayError(error.responseText);
}

function queryErrorHandle(jsonData) {
	if (jsonData['error_type'] != null) {
		displayError(jsonData['error_type'], jsonData['error_msg']);
		return true;
	}
	else {
		return false;
	}
}

function taskQuery(projectID, taskID, changes, callback) {
	$.ajax({
		url: "query/task_update.php",
		type: "post",
		dataType: "json",
		data: {
			projectID: projectID,
			taskID: taskID,
			changes: changes
		},
		success: function(data) {
			if (queryErrorHandle(data)) { return; }
			callback(data);
		},
		error: ajaxErrorHandle
	});
}

function createTask(projectID, changes, callback) {
	changes['action'] = 'create';
	taskQuery(projectID, -1, changes, callback);
}

function updateTask(projectID, taskID, changes, callback) {
	changes['action'] = 'update';
	taskQuery(projectID, taskID, changes, callback);
}