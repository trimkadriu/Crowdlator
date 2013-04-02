<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
    	List of projects
	</h3><hr/>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th style="width:5%;">ID</th>
                    <th style="width:20%;">Name</th>
                    <th style="width:35%;">Description</th>
                    <th style="width:15%;">From</th>
                    <th style="width:15%;">To</th>
                    <th style="width:10%; text-align: center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($projects != null) { $index = 0; foreach($projects as $project) { ?>
                <tr id="<?php echo $project->id; ?>">
                    <td><?php echo $project->id; ?></td>
                    <td><?php echo $project->project_name; ?></td>
                    <td><?php echo $project->project_description; ?></td>
                    <td><?php echo $project->translate_from_language; ?></td>
                    <td><?php echo $project->translate_to_language; ?></td>
                    <td style="text-align: center">
                        <?php if(check_permissions(get_session_roleid(), 'admin/projects/edit_project')) { ?>
                            <a href="<?php echo base_url('admin/projects/edit_project/'.$project->id); ?>" rel="tooltip" data-placement="top" data-original-title="Edit project details">
                            <i class="icon-edit"></i></a>
                        <?php } ?>
                        <?php if(check_permissions(get_session_roleid(), 'admin/projects/assign_editors')) { ?>
                            <a href="<?php echo base_url('admin/projects/assign_editors/'.$project->id); ?>" rel="tooltip" data-placement="top" data-original-title="Assign editors to project tasks">
                            <i class="icon-tasks"></i></a>
                        <?php } ?>
                        <?php if(check_permissions(get_session_roleid(), 'admin/projects/delete_project')) { ?>
                            <a onclick="delete_confirm(<?php echo $project->id.', \''.$project->project_name.'\''; ?>);"
                               href="#confirm_delete_modal" rel="tooltip" data-placement="top" data-original-title="Delete project"
                               data-toggle="modal">
                            <i class="icon-remove"></i></a>
                        <?php } ?>
                        <?php if(check_permissions(get_session_roleid(), 'admin/projects/tasks_list')) { ?>
                            <a href="<?php echo base_url('admin/projects/tasks_list/'.$project->id); ?>" rel="tooltip" data-placement="top" data-original-title="List project tasks">
                            <i class="icon-th-list"></i></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php $index++; } } else {?>
                <tr><td colspan="6">No projects found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php if(check_permissions(get_session_roleid(), 'admin/projects/delete_project')) { ?>
    <input type="hidden" id="row_id" value=""/>
    <div class="modal hide fade" id="confirm_delete_modal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Confirm</h3>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete <strong class="proj_name"></strong>?</p>
        </div>
        <div class="modal-footer">
            <a class="btn" data-dismiss="modal">Cancel</a>
            <a id="delete_confirmed" class="btn btn-primary">Delete</a>
        </div>
    </div>
    <script>
        function delete_confirm(id, name) {
            $('#row_id').val(id);
            $('.proj_name').text(name);
        }

        $('#delete_confirmed').click(function(){
            window.location.replace("<?php echo base_url('admin/projects/delete_project'); ?>" + '/' + $('#row_id').val());
        });
    </script>
<?php } ?>
<script>
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();
    });
</script>
<?php $this->load->view('_inc/datatables'); ?>
<?php $this->load->view('_inc/footer_base'); ?>