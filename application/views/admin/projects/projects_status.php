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
                    <th style="width:13%;">Status</th>
                    <th style="width:17%; text-align: center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if($projects_nr > 0) { for($i = 0; $i < $projects_nr; $i++) { ?>
                <tr id="row_<?php echo $project_id[$i]; ?>">
                    <td>
                        <span style="display: none;"><?php echo $date_created[$i]; ?></span>
                        <?php echo $project_name[$i]; ?><br/><?php echo $date_created[$i]; ?>
                    </td>
                    <td><?php echo $translate_from[$i]; ?></td>
                    <td><?php echo $translate_to[$i]; ?></td>
                    <td style="text-align: center">
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
                        <?php if($status[$i] == "Finished") { ?>
                        <script>$("#row_<?php echo $project_id[$i]; ?> td:nth-child(4)").find('div.progress').removeClass('active progress-striped');</script>
                        <a onclick="prepare_final_video_modal('<?php echo $project_id[$i]; ?>', '<?php echo $video_id[$i]; ?>');"
                           href="#final_video_modal" rel="tooltip" data-placement="top" data-original-title="Generate final video."
                           data-toggle="modal" class="btn" style="margin-bottom: 5px;">
                            <i class="icon-download"></i> Download Final Video
                        </a><br/>
                        <?php } ?>
                    </td>
                    <td><?php echo ucfirst(strtolower($status[$i])); ?></td>
                    <td style="text-align: left">
                        <a onclick="prepare_video_modal('<?php echo $video_id[$i]; ?>', '<?php echo $project_name[$i]; ?>', $('#description_<?php echo $i; ?>').val());"
                           href="#video_modal" rel="tooltip" data-placement="top" data-original-title="Open video of this project." data-toggle="modal">
                            <i class="icon-film"></i> Watch video
                        </a>
                        <textarea id="description_<?php echo $i; ?>" style="display: none;">
                            <?php echo trim(strip_quotes(strip_slashes(trim($project_description[$i])))); ?>
                        </textarea>
                        <textarea id="original_text_<?php echo $i; ?>" style="display: none;"><?php echo trim(strip_quotes(strip_tags($original_text[$i]))); ?>
                        </textarea>
                        <textarea id="translated_text_<?php echo $i; ?>" style="display: none;"><?php echo trim(strip_quotes(strip_tags($translated_text[$i]))); ?>
                        </textarea>
                        <br/>
                        <?php if($status[$i] != "In Translation") { ?>
                        <a onclick="prepare_translated_text_modal('<?php echo $project_name[$i]; ?>', $('#original_text_<?php echo $i; ?>').val(), $('#translated_text_<?php echo $i; ?>').val());"
                           href="#translated_text" rel="tooltip" data-placement="top" data-original-title="See the translated text." data-toggle="modal">
                            <i class="icon-text-width"></i> Translated text
                        </a><br/>
                        <?php } ?>
                        <?php if($status[$i] == "Finished") { ?>
                        <a onclick="prepare_recorded_audio_modal('<?php echo $project_name[$i]; ?>', $('#translated_text_<?php echo $i; ?>').val(), '<?php echo $audio_id[$i]; ?>');"
                           href="#audio_modal" rel="tooltip" data-placement="top" data-original-title="Listen the recorded audio." data-toggle="modal">
                            <i class="icon-play-circle"></i> Recorded audio
                        </a><br/>
                        <?php } ?>
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
<div class="modal hide fade" id="translated_text">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="project_name_modal"></h3>
    </div>
    <div class="modal-body">
        <strong>Original text:</strong><br/>
        <textarea disabled="disabled" id="original_text_modal" class="translation_text"></textarea><br/>
        <strong>Translated text:</strong><br/>
        <textarea disabled="disabled" id="translated_text_modal" class="translation_text"></textarea>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
    </div>
</div>
<div class="modal hide fade" id="audio_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 id="project_name_audio_modal"></h3>
    </div>
    <div class="modal-body">
        <strong>Translated text:</strong><br/>
        <textarea disabled="disabled" id="translated_text_audio_modal" class="translation_text"></textarea><br/>
        <strong>Recorded audio for translated text:</strong><br/>
        <iframe id="soundcloud_player" width="100%" height="166" scrolling="no" frameborder="no" src=""></iframe>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
    </div>
</div>
<div class="modal hide fade" id="final_video_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Generate final video</h3>
    </div>
    <div class="modal-body">
        <p class="">Please don't close this window while generating video is in progress.</p>
        <div class="progress progress-striped active" id="generate_video_progress" style="display: none;">
            <div class="bar" style="width: 100%;">Generating video, please wait...</div>
        </div>
        <input type="hidden" id="generate_video_project_id">
        <input type="hidden" id="generate_video_video_id">
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Cancel</a>
        <a class="btn btn-primary" id="generate_video">Generate Video</a>
    </div>
</div>
<style>
    .translation_text {
        width: 97%;
        height: 150px;
    }
</style>
<script>
    $(document).ready(function () {
        $("[rel=tooltip]").tooltip();

        $("#generate_video").click(function () {
            $("#generate_video_progress").show();
            id = $("#generate_video_project_id").val();
            video_id = $("#generate_video_video_id").val();
            $.get('<?php echo base_url("admin/projects/generate_video"); ?>/' + id, function(data) {
                var obj = $.parseJSON(data);
                if(obj.return_result == true) {
                    url = "<?php echo base_url('admin/projects/generate_download_video_link'); ?>?project_id=" + id + "&video_id=" + video_id;
                    window.open(url, '', 'width=100,height=30');
                    $("#generate_video_progress").hide();
                }
                else
                    window.location.href = "<?php echo base_url('admin/projects/projects_status'); ?>";
            });
        });
    });

    function prepare_video_modal(video_id, project_name, project_description) {
        $("#video").attr("src", "http://www.youtube.com/embed/" + video_id);
        $("#video_modal_title").text("Video of '" + project_name + "'");
        $("#description").text(project_description);
    }

    function prepare_translated_text_modal(project_name, original_text, translated_text) {
        $("#project_name_modal").text(project_name);
        $("#original_text_modal").text(original_text);
        $("#translated_text_modal").text(translated_text);
    }

    function prepare_recorded_audio_modal(project_name, translated_text, audio_id) {
        $("#project_name_audio_modal").text(project_name);
        $("#translated_text_audio_modal").val(translated_text);
        url = "https://w.soundcloud.com/player/?url=http://api.soundcloud.com/tracks/" + audio_id + "&color=ff6600&auto_play=false&show_artwork=false";
        $("#soundcloud_player").attr("src", url);
    }

    function prepare_final_video_modal(project_id, video_id) {
        $("#generate_video_project_id").val(project_id);
        $("#generate_video_video_id").val(video_id);
    }
</script>
<?php $this->load->view('_inc/datatables'); ?>
<script>
    $(document).ready(function() {
        $(".datatable thead tr th:nth-child(1)").click();
    });
</script>
<?php $this->load->view('_inc/footer_base'); ?>