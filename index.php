<?php
session_start();
include_once 'auth/auth_utils.php';
include_once 'lib/TaskDB.php';
include_once 'lib/template.php';

$user = get_user_id();
if ($user == -1){
	header('location: auth/login.php');
}

$db_connection = new TaskDB();
//retrieve data
$projects = array();
$stmt = $db_connection->prepare('
SELECT 
	p.ProjectID,
    ProjectName,
    t.TaskID,
    Task,
    IsFinished
FROM tbl_project_collaborators AS c LEFT JOIN
	tbl_projects AS p ON p.ProjectID=c.ProjectID LEFT JOIN
	tbl_tasks AS t ON p.ProjectID=t.ProjectID
WHERE UserID=?
ORDER BY p.ProjectID, t.TaskID
');
$stmt->bind_param("i", $user);
$stmt->execute();
$stmt->bind_result($query_project_id, $query_name, $query_task_id, $query_task, $query_finished);
while($stmt->fetch()){
	if (isset($projects[$query_project_id])){ 
		$projects[$query_project_id]["tasks"][$query_task_id] = array(
			"task" => $query_task,
			"finished" => $query_finished
		);
	}
	else{
		$projects[$query_project_id] = array(
			"name" => $query_name,
			"tasks" => array(
				$query_task_id => array( 
					"task" => $query_task, 
					"finished" => $query_finished
				)
			)
		);	
	}
}
$stmt->close();

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="/extlib/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="/extlib/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" />
	<link href="task.css" rel="stylesheet"/>
</head>
<body>
<?php write_quick_task_modal() ?>
<div id="projects-container" class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="input-group">
				<input type="text" placeholder="Search Project" class="form-control">
				<div class="input-group-btn">
					<button class="btn btn-primary create-project-button">Search</button>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<form action="query/project_update.php" method="POST">
				<div class="input-group">
					<input type="text" name="projectName" placeholder="Create Project" class="form-control project-create">
					<div class="input-group-btn">
						<button class="btn btn-primary project-create-button">Create</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="grid">
		<div class="grid-size"></div>
		<?php foreach ($projects as $project_id => $project_data) { ?>
		<div class="grid-item">
			<?php write_project_panel($project_id, $project_data); ?>
		</div>
		<?php } ?>
	</div>
</div>
<div id="project-container" class="container-fluid">
<div class="row">
<div class="col-md-6">
	<div class="task-list">
		
	</div>
</div>
<div class="col-md-6">
	<div class="task-area">
		
	</div>
</div>
</div>
</div>

<script src="/extlib/sprintf.min.js"></script>
<script src="/extlib/jquery-2.2.4.min.js"></script>
<script src="/extlib/bootstrap/js/bootstrap.min.js"></script>
<script src="/extlib/masonry.pkgd.min.js"></script>
<script src="task.js"></script>
<script type="text/javascript">
	var taskEntryTemplate = '<?= get_tasklist_entry_template() ?>';
	var $grid = null;

	$(document).ready(function () {
		$("#quick-task").on("click", ".create-task", function(){
			var $this = $(this);
			var $modal = $("#quick-task");
			var projectID = $modal.attr("data-project-id");
			$.ajax({
				url: "query/task_update.php",
				type: "POST",
				dataType: "json",
				data: {
					projectID: projectID,
					taskID: -1,
					task: $modal.find(".task-name").val()
				},
				success: function(data){
					if (data["error_type"] != null){
						alert("unable to update task: " + data["error_type"] + ": " + data["error_msg"]);
						return;
					}
					$(".project[data-project-id='" + projectID + "'] .project-tasks").append(
						sprintf(taskEntryTemplate, data["id"], "", data["task"])
					);
					$modal.modal("hide");
					$grid.masonry();
				},
				error: function(response){
					alert(response.responseText);
				}
			});
		});

		//init masonry
		$grid = $(".grid").masonry({
			columnWidth: ".grid-size",
			itemSelector: ".grid-item",
			percentPosition: true,
			gutter: 30
		});


	}).on("click", ".task-entry .checkmark", function(){
		var $this = $(this);
		//check if currently making a request
		if ($this.attr("data-update-state") == "sending") { 
			return;
		}

		$this.attr("data-update-state", "sending");

		//send AJAX request
		$.ajax({
			url: "query/task_update.php",
			type: "post",
			dataType: "json",
			data: {
				projectID: $this.closest(".project").attr("data-project-id"),
				taskID: $this.closest(".task-entry").attr("data-entry-id"),
				finished: $this.hasClass("checked") ? 0 : 1
			},
			success: function(data){
				$this.attr("data-update-state", "");
				if (data["error_type"] != null){
					alert("unable to update task: " + data["error_type"]);
					return;
				}
				//visually show new state
				if ($this.hasClass("checked")){
					$this.removeClass("checked");
				}
				else{
					$this.addClass("checked");
				}
			},
			error: function(response){
				alert(response.responseText);
			}
		});

	}).on("click", ".project[data-project-id] .project-quick-add", function(){
		//attach data to modal
		$("#quick-task").attr("data-project-id", $(this).closest(".project").attr("data-project-id"));
		//open modal
		clearQuickTaskModal();
		$("#quick-task").modal('show');
	}).on("click", ".project[data-project-id] .project-goto", function(){
		//load project data into project container


		//switch page
		$("#projects-container").addClass("hidden");
		$("#project-container").removeClass("hidden");
	}).on("click", ".task-body-options .task-text", function(){
		var body = $(this).parent().siblings(".task-body");
		body.append("<textarea class='form-control'></textarea>");
	});
</script>
</body>
</html>