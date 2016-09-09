<?php

function write_quick_task_modal() { ?>
<div id="quick-task" class="modal fade"> 
	<div class="modal-dialog" role="document">
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal">
      				<span>&times;</span>
    			</button>
    			<h3 class="modal-title">Add Quick Task</h3>
  			</div>
      		<div class="modal-body">
      			<div class="">
      				<input type="text" placeholder="Enter Task" class="form-control task-name">
  				</div>
				<div class="task-body">
				</div>
				<div class="task-body-options pull-right">
					<button class="task-text btn btn-default">Text</button> 					
					<button class="task-list btn btn-default">List</button>
					<button class="task-md btn btn-default">Markdown</button>
					<button class="task-dependency btn btn-default">Dependency</button>
  				</div>
  				<h3>Items</h3>
      		</div>
      		<div class="modal-footer">
				<button type="button" class="btn btn-primary create-task">Create</button>
      		</div>
      	</div>
    </div>
</div>
<?php } 

function write_confirmation_modal() { ?>
<div id="confirmation" class="modal fade"> 
	<div class="modal-dialog">
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal">
      				<span>&times;</span>
    			</button>
    			<h3 class="modal-title">Confirmation</h3>
  			</div>
      		<div class="modal-body">
      		</div>
      		<div class="modal-footer">
				<button type="button" class="btn btn-primary option-cancel" data-dismiss="modal">No</button>
				<button type="button" class="btn btn-primary option-confirm">Yes</button>
      		</div>
      	</div>
    </div>
</div>
<?php }

function write_project_panel($project_id, $project_data){ ?>
<div class="panel panel-default project" data-project-id="<?= $project_id ?>">
	<div class="panel-heading">
		<h4 class="project-title"><?= $project_data['name'] ?></h4>
		<div class="project-options">
			<button class="project-quick-add btn btn-primary">
				<span class="option-desc">Quick Task </span>
				<i class="fa fa-plus"></i>
			</button>
			<button class="project-goto btn btn-info">
				<span class="option-desc">Open </span>
				<i class="fa fa-book"></i>
			</button>
			<button class="project-archive btn btn-danger">
				<span class="option-desc">Archive </span>
				<i class="fa fa-archive"></i>
			</button>
		</div>
		
	</div>
	<div class="panel-body">
		<div class="project-tasks">
			<?php 
			$task_html = '';
			$task_complete_html = '';
			foreach ($project_data["tasks"] as $task_id => $task_data) {
				if ($task_data["finished"]){
					$task_complete_html .= sprintf(get_tasklist_entry_template(),
						$task_id,
						"checked",
						$task_data["task"]
					);
				}
				else {
					$task_html .= sprintf(get_tasklist_entry_template(),
						$task_id,
						"",
						$task_data["task"]
					);
				}
			}
			echo $task_html;
			?>
		</div>
		<div class="project-tasks-completed-show <?= strlen($task_complete_html) == 0 ? "hidden" : "" ?>">Completed Tasks</div>
		<div class="project-tasks-completed collapse">
			<?php echo $task_complete_html; ?>
		</div>
	</div>
</div>
<?php } 

function compact_template($template){
	$template = str_replace("\r\n", "", $template);
	$template = str_replace("\n", "", $template);
	$template = str_replace("\t", "", $template);
	return $template;
}

function get_tasklist_entry_template(){
	return compact_template('
		<div class="task-entry" data-entry-id="%d">
			<div class="checkmark checkmark-box %s"><i class="fa fa-check"></i></div>
			<div class="task-title">%s</div>
		</div>
	');
}

?>