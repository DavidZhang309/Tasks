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