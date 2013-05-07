<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
        Choose best translations for projects
    </h3><hr/>
    <ul class="nav nav-tabs">
        <li <?php if($make_active == 0) echo 'class="active"'; ?>>
            <a href="<?php echo base_url("admin/translate/choose_translations/0"); ?>">Text translations</a>
        </li>
        <li <?php if($make_active == 1) echo 'class="active"'; ?>>
            <a href="<?php echo base_url("admin/translate/choose_translations/1"); ?>">Audio translations</a>
        </li>
    </ul>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
            <tr>
                <th style="width:20%;">Project name<br/>Date translated</th>
                <th style="width:15%;">From</th>
                <th style="width:15%;">To</th>
                <th style="width:10%; text-align: center;">Votes:<br/>Good / Bad</th>
                <th style="width:8%; text-align: center;">Choose Translation</th>
                <th style="width:15%; text-align: center;">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if($audios_nr > 0) { for($i = 0; $i < $audios_nr; $i++) { ?>
            <tr>
                <td><?php echo $project_name[$i]; ?><br/><?php echo $date_recorded[$i]; ?></td>
                <td><?php echo $translate_from[$i]; ?></td>
                <td><?php echo $translate_to[$i]; ?></td>
                <td style="text-align: center">
                    <span style="display: none"><?php echo $good_votes[$i] - $bad_votes[$i]; ?></span>
                    <span class="badge badge-success"><?php echo $good_votes[$i]; ?></span> /
                    <span class="badge badge-important"><?php echo $bad_votes[$i]; ?></span>
                </td>
                <td style="text-align: center">
                    <ul class="nav nav-pills">
                        <li>
                            <a style="cursor: pointer;" href="#choose_modal" data-toggle="modal"
                               onclick="prepare_choose_modal('<?php echo $id[$i]; ?>')">
                                Choose Translation
                            </a>
                        </li>
                    </ul>
                </td>
                <td style="text-align: left">
                    <?php
                        $translated_text = strip_quotes(str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $translated[$i]));
                        $from = $translate_from[$i];
                        $to = $translate_to[$i];
                    ?>
                    <a rel="tooltip" data-placement="top" data-original-title="Review this translation"
                       data-toggle="modal" href="#review_modal" onclick="prepare_review_modal('<?php echo $translated_text; ?>',
                            '<?php echo $audio_id[$i]; ?>', '<?php echo $from; ?>', '<?php echo $to; ?>');">
                        <i class="icon-check"></i> Review
                    </a><br/>
                    <a onclick="prepare_video_modal('<?php echo $project_video_id[$i]; ?>', '<?php echo $project_name[$i]; ?>', $('#description_<?php echo $i; ?>').val());"
                       href="#video_modal" rel="tooltip" data-placement="top" data-original-title="Open video of this project." data-toggle="modal">
                        <i class="icon-film"></i> See video
                    </a>
                    <textarea id="description_<?php echo $i; ?>" style="display: none;">
                        <?php echo strip_quotes(strip_slashes(trim($project_description[$i]))); ?>
                    </textarea><br/>
                </td>
            </tr>
                <?php } } else {?>
            <tr><td colspan="6">No translations found.</td></tr>
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
<div class="modal hide fade" id="review_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Review</h3>
    </div>
    <div class="modal-body">
            <strong>Translated from <span id="vote_modal_from"></span> to <span class="vote_modal_to"></span>:</strong><br/>
            <textarea id="vote_modal_translated" disabled="disabled" class="translate_text"></textarea>
            <strong>Audio recorded in <span class="vote_modal_to"></span>:</strong><br/>
            <iframe id="soundcloud_player" width="100%" height="166" scrolling="no" frameborder="no" src=""></iframe>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
    </div>
</div>
<div class="modal hide fade" id="choose_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Choose translation confirmation</h3>
    </div>
    <div class="modal-body">
        <p>Are you sure you want to choose this translation for its task as the best translation ?</p>
    </div>
    <div class="modal-footer">
        <a class="btn btn-danger" data-dismiss="modal">No</a>
        <a class="btn btn-success" id="choose_confirm">Yes</a>
    </div>
</div>
<script>
    function prepare_video_modal(video_id, project_name, project_description) {
        $("#video").attr("src", "http://www.youtube.com/embed/" + video_id);
        $("#video_modal_title").text("Video of '" + project_name + "'");
        $("#description").text(project_description);
    }

    function prepare_review_modal(translated, audio_id, from, to) {
        $("#vote_modal_translated").val(translated);
        $("#vote_modal_from").val(from);
        $(".vote_modal_to").val(to);
        url = "https://w.soundcloud.com/player/?url=http://api.soundcloud.com/tracks/" + audio_id + "&color=ff6600&auto_play=false&show_artwork=false";
        $("#soundcloud_player").attr("src", url);
    }

    function prepare_choose_modal(id) {
        $("#choose_confirm").attr("href", "<?php echo base_url('admin/translate/choose_audio'); ?>/" + id);
    }

    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();
    });
</script>
<style>
    .translate_text {
        width: 97%;
        height: 150px;
        margin-top: 5px;
        resize: none;
    }
</style>
<?php $this->load->view('_inc/datatables'); ?>
<script>
    $(document).ready(function() {
        $(".datatable thead tr th:nth-child(4)").click().click();
    });
</script>
<?php $this->load->view('_inc/footer_base'); ?>