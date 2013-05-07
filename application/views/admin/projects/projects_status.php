<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
    	List of projects statuses
	</h3><hr/>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th style="width:20%;">Project Name<br/>Date Created</th>
                    <th style="width:10%;">From</th>
                    <th style="width:10%;">To</th>
                    <th style="width:30%;">Progress</th>
                    <th style="width:15%;">Status</th>
                    <th style="width:15%; text-align: center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if($projects_nr > 0) { for($i = 0; $i < $projects_nr; $i++) { ?>
                <tr>
                    <td>
                        <span style="display: none;"><?php echo $date_created[$i]; ?></span>
                        <?php echo $project_name[$i]; ?><br/><?php echo $date_created[$i]; ?>
                    </td>
                    <td><?php echo $translate_from[$i]; ?></td>
                    <td><?php echo $translate_to[$i]; ?></td>
                    <td>
                        <?php
                            $percentage = 1;
                            if($status[$i] == "In Translation")
                                $percentage = 33;
                            if($status[$i] == "In Audition")
                                $percentage = 66;
                            if($status[$i] == "Finished")
                                $percentage = 100;
                        ?>
                        <div class="progress progress-striped progress-success active">
                            <div class="bar" style="width: <?php echo $percentage; ?>%;"></div>
                        </div>
                    </td>
                    <td><?php echo ucfirst(strtolower($status[$i])); ?></td>
                    <td style="text-align: center">
                        <a onclick="prepare_video_modal('<?php echo $video_id[$i]; ?>', '<?php echo $project_name[$i]; ?>', $('#description_<?php echo $i; ?>').val());"
                           href="#video_modal" rel="tooltip" data-placement="top" data-original-title="Open video of this project." data-toggle="modal">
                            <i class="icon-film"></i> See video
                        </a>
                        <textarea id="description_<?php echo $i; ?>" style="display: none;">
                            <?php echo strip_quotes(strip_slashes(trim($project_description[$i]))); ?>
                        </textarea><br/>
                    </td>
                </tr>
                <?php } } else { ?>
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
        <!-- End of Video Here -->
        <div>
            <span style="text-decoration: underline;">Description:</span>
            <span id="description"></span>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();
    });

    function prepare_video_modal(video_id, project_name, project_description) {
        $("#video").attr("src", "http://www.youtube.com/embed/" + video_id);
        $("#video_modal_title").text("Video of '" + project_name + "'");
        $("#description").text(project_description);
    }
</script>
<?php $this->load->view('_inc/datatables'); ?>
<script>
    $(document).ready(function() {
        $(".datatable thead tr th:nth-child(1)").click();
    });
</script>
<?php $this->load->view('_inc/footer_base'); ?>