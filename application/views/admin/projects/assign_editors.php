<?php $this->load->view('_inc/header_admin'); ?>
<script src="<?php echo base_url("template/js/jquery.tokeninput.js"); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url("template/css/token-input-facebook.css"); ?>"/>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
    	Assign tasks to editors for "<?php echo $project[0]->project_name; ?>"
	</h3><hr/>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th style="width:5%; text-align: center">ID</th>
                    <th style="width:65%;">Text</th>
                    <th>Editor</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 0; foreach($tasks as $task) { ?>
                <tr>
                    <td style="text-align: center">
                        <?php echo $task->id; ?>
                    </td>
                    <td>
                        <?php echo $task->text; ?>
                    </td>
                    <td>
                        <a rel="tooltip" data-placement="top" data-original-title="Delete this task." data-toggle="modal"
                            onclick="prepare_delete_confirm('<?php echo $task->id; ?>');" href="#delete_task_modal">
                            <i class="icon-remove"></i> Delete task
                        </a><br/><br/>
                        <div id="div_input_tag" style="position: relative;">
                            <div class="input_tag_overlay" style="display: none;" id="overlay_<?php echo $task->id; ?>">
                                <span class="pull-right">Editor is set</span>
                            </div>
                            <textarea id="tag_input<?php echo $task->id; ?>" rows="1"></textarea><br/>
                        </div>
                        <div class="pull-right">
                            <img id="loading<?php echo $task->id; ?>" style="display: none;" src="<?php echo base_url("template/img/loading.gif"); ?>"/>
                            <a style="cursor: pointer; <?php if(empty($editor_username[$index][0])){?> display: none; <?php } ?>"
                               class="temp-link" id="edit_<?php echo $task->id; ?>" onclick="edit_input_tag(<?php echo $task->id; ?>);">
                                <span class="badge badge-info">
                                    <i class="icon-pencil icon-white"></i> Edit
                                </span>
                            </a>
                            <a style="cursor: pointer; <?php if(!empty($editor_username[$index][0])){?> display: none; <?php } ?>"
                               class="temp-link" id="save_<?php echo $task->id; ?>" onclick="update_editor_ajax('<?php echo $task->id; ?>');">
                                <span class="badge badge-info">
                                        <i class="icon-ok icon-white"></i> Save
                                </span>
                            </a>
                        </div>
                    </td>
                    <script type="text/javascript">
                        var task_id = '<?php echo $task->id;?>';
                        $("#tag_input" + task_id).tokenInput("<?php echo base_url("admin/editors/get_editors_filter"); ?>", {
                            <?php if(!empty($editor_username[$index][0])) { ?>
                            prePopulate: [{id: "<?php echo $editor_username[$index][0]->id; ?>", name: "<?php echo $editor_username[$index][0]->username; ?>"}],
                            <?php } ?>
                            theme: "facebook",
                            searchDelay: 500,
                            minChars: 2,
                            tokenLimit: 1
                        });
                        <?php if(!empty($editor_username[$index][0])) { ?>
                            $('#overlay_<?php echo $task->id; ?>').show();
                        <?php } ?>
                    </script>
                <?php $index++; } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal hide fade" id="delete_task_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Delete confirm</h3>
    </div>
    <div class="modal-body">
        <p>
            Are you sure you want to delete this task ?
        </p>
        <input type="hidden" id="task_id">
    </div>
    <div class="modal-footer">
        <a class="btn btn-danger" data-dismiss="modal">No</a>
        <a class="btn btn-success" id="delete_task">Yes</a>
    </div>
</div>
<?php $this->load->view('_inc/datatables'); ?>
<script>
    $(document).ready(function () {
        $("#delete_task").click(function() {
            task_id = $("#task_id").val();
            window.location.href = "<?php echo base_url('admin/projects/delete_task'); ?>/" + task_id;
        });
    });
    function prepare_delete_confirm(task_id) {
        $("#task_id").val(task_id);
    }

    function edit_input_tag(id) {
        $('#overlay_' + id).hide();
        $('#tag_input' + id).tokenInput("clear");
        $('#edit_' + id).hide();
        $('#save_' + id).show();
        //update_editor_ajax(id); //This option automatically set null editor when click edit
    }

    function update_editor_ajax(id){
        var editor_name_tag = $('#tag_input' + id).tokenInput("get")[0];
        if(editor_name_tag == undefined)
            var editor_name = "";
        else
            var editor_name = editor_name_tag.name;
        $("#loading" + id).show();
        $.post('<?php echo base_url("admin/projects/assign_editors_ajax") ?>', {
            task_id: id,
            editor: editor_name,
            project_id: '<?php echo $project[0]->id; ?>'
        }).done(function(data){
            var response = $.parseJSON(data);
            if(response.msg == "true"){
                if($('#tag_input' + id).tokenInput("get")[0] != undefined){
                    $("#overlay_" + id).show();
                    $('#edit_' + id).show();
                    $('#save_' + id).hide();
                }
                $("#loading" + id).hide();
            }
            if(response.msg == "fail")
                location.reload();
        }, "json");
    }
</script>
<style>
    .temp-link:hover {text-decoration: none;}
</style>
<?php $this->load->view('_inc/footer_base'); ?>