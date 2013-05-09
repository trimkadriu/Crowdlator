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
                    <!--<th style="width:5%;">ID</th>-->
                    <th style="width:20%;">Name<br/>Date created</th>
                    <th style="width:30%;">Description</th>
                    <th style="width:15%;">From</th>
                    <th style="width:15%;">To</th>
                    <th style="width:20%; text-align: center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if($projects != null) { $index = 0; foreach($projects as $project) { ?>
                <?php if(get_user_role() == "translator" && $project->status == "Finished"); else{ ?>
                <tr id="<?php echo $project->id; ?>">
                    <td>
                        <span style="display: none"><?php echo $project->create_date; ?></span>
                        <?php echo $project->project_name; ?><br/><?php echo $project->create_date; ?>
                    </td>
                    <td><?php echo $project->project_description; ?></td>
                    <td><?php echo $project->translate_from_language; ?></td>
                    <td><?php echo $project->translate_to_language; ?></td>
                    <td style="text-align: left">
                        <?php if(check_permissions(get_session_roleid(), 'admin/projects/edit_project')) { ?>
                            <a href="<?php echo base_url('admin/projects/edit_project/'.$project->id); ?>" rel="tooltip" data-placement="top" data-original-title="Edit project details">
                            <i class="icon-edit"></i> Edit details</a><br/>
                        <?php } ?>
                        <?php if(check_permissions(get_session_roleid(), 'admin/projects/assign_editors') && $project->status == "In Translation") { ?>
                            <a href="<?php echo base_url('admin/projects/assign_editors/'.$project->id); ?>" rel="tooltip" data-placement="top" data-original-title="Assign editors to project tasks">
                            <i class="icon-tasks"></i> Assign editors</a><br/>
                        <?php } ?>
                        <?php if(check_permissions(get_session_roleid(), 'admin/projects/delete_project')) { ?>
                            <a onclick="delete_confirm(<?php echo $project->id.', \''.$project->project_name.'\''; ?>);"
                               href="#confirm_delete_modal" rel="tooltip" data-placement="top" data-original-title="Delete project"
                               data-toggle="modal">
                            <i class="icon-remove"></i> Delete project</a><br/>
                        <?php } ?>
                        <?php if($project->status == "In Audition") { ?>
                            <?php if(check_permissions(get_session_roleid(), 'admin/translate/audio_audition')) { ?>
                            <a href="<?php echo base_url('admin/translate/audio_audition/'.$project->id); ?>" rel="tooltip" data-placement="top" data-original-title="Record audio">
                                <img src="<?php echo base_url("template/img/extra_icons/glyphicons_300_microphone.png"); ?>"
                                        style="height: 13px; width: 8px;"/> Record audio</a><br/>
                        <?php } ?>
                            <span rel="tooltip" data-placement="top" data-original-title="Ask people to record audio for this project by sharing it on Facebook.">
                                <a href="#" onclick="facebook_share('<?php echo urlencode(base_url("public/translate/audio")."/".$project->id); ?>', $(this).parent().attr('data-original-title'))">
                                    <img src="<?php echo base_url("template/img/extra_icons/glyphicons_410_facebook.png"); ?>"
                                         style="width: 13px; height: 13px"/> Facebook
                                </a>
                            </span><br/>
                            <span rel="tooltip" data-placement="top" data-original-title="Ask people to record audio for this project by sharing it on Twitter.">
                                <a href="#" onclick="twitter_share('<?php echo urlencode(base_url("public/translate/audio")."/".$project->id) ?>', $(this).parent().attr('data-original-title')) ">
                                    <img src="<?php echo base_url("template/img/extra_icons/glyphicons_411_twitter.png"); ?>"
                                         style="width: 13px; height: 13px"/> Twitter
                                </a>
                            </span><br/>
                        <?php } elseif($project->status == "In Translation") { ?>
                            <?php if(check_permissions(get_session_roleid(), 'admin/projects/tasks_list')) { ?>
                                <a href="<?php echo base_url('admin/projects/tasks_list/'.$project->id); ?>" rel="tooltip" data-placement="top" data-original-title="List project tasks">
                                <i class="icon-th-list"></i> View tasks</a><br/>
                            <?php } ?>
                            <span rel="tooltip" data-placement="top" data-original-title="Ask people to translate text for this project by sharing it on Facebook.">
                                <a href="#" onclick="facebook_share('<?php echo urlencode(base_url("public/translate/project")."/".$project->id); ?>', $(this).parent().attr('data-original-title'))">
                                    <img src="<?php echo base_url("template/img/extra_icons/glyphicons_410_facebook.png"); ?>"
                                         style="width: 13px; height: 13px"/> Facebook
                                </a>
                            </span><br/>
                            <span rel="tooltip" data-placement="top" data-original-title="Ask people to translate text for this project by sharing it on Twitter.">
                                <a href="#" onclick="twitter_share('<?php echo urlencode(base_url("public/translate/project")."/".$project->id) ?>', $(this).parent().attr('data-original-title')) ">
                                    <img src="<?php echo base_url("template/img/extra_icons/glyphicons_411_twitter.png"); ?>"
                                         style="width: 13px; height: 13px"/> Twitter
                                </a>
                            </span><br/>
                        <?php } ?>
                        <a onclick="prepare_video_modal('<?php echo $project->video_id; ?>', '<?php echo $project->project_name; ?>');"
                           href="#video_modal" rel="tooltip" data-placement="top" data-original-title="Open video of this project."
                           data-toggle="modal">
                            <i class="icon-film"></i> Watch video</a>
                    </td>
                </tr>
                <?php } $index++; } } else {?>
                <tr><td colspan="6">No projects found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal hide fade" id="video_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="video_modal_title"></h3>
    </div>
    <div class="modal-body">
        <!-- Youtube Video Here -->
        <iframe id="video" width="530" height="298" frameborder="0" allowfullscreen></iframe>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
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
<?php $this->load->view('_inc/datatables'); ?>
<script>
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();

        $(".datatable thead tr th:nth-child(1)").click();
    });

    function prepare_video_modal(video_id, project_name) {
        $("#video").attr("src", "http://www.youtube.com/embed/" + video_id);
        $("#video_modal_title").text("Video of '" + project_name + "'");
    }

    function facebook_share(url, text) {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, '', 'width=600,height=300');
    }

    function twitter_share(url, text) {
        window.open('https://twitter.com/intent/tweet?text=' + text + ' ' + url, '', 'width=600,height=300');
    }
</script>
<?php $this->load->view('_inc/footer_base'); ?>