<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
        List of <?php if($make_active == 0) echo 'translations'; elseif($make_active == 1) echo 'audios'; ?> to vote
    </h3><hr/>
    <ul class="nav nav-tabs">
        <li <?php if($make_active == 0) echo 'class="active"'; ?>>
            <a href="<?php echo base_url("admin/translate/vote_translations/0"); ?>">Text translations</a>
        </li>
        <li <?php if($make_active == 1) echo 'class="active"'; ?>>
            <a href="<?php echo base_url("admin/translate/vote_translations/1"); ?>">Audio translations</a>
        </li>
    </ul>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
            <tr>
                <th style="width:20%;">Project name<br/>Date recorded</th>
                <th style="width:15%;">From</th>
                <th style="width:15%;">To</th>
                <th style="width:8%; text-align: center;">Voted</th>
                <th style="width:10%; text-align: center;">Votes:<br/>Good / Bad</th>
                <th style="width:15%; text-align: center;">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if($audios_nr > 0) { for($i = 0; $i < $audios_nr; $i++) { ?>
            <?php if($project_status[$i] != "Finished" && $project_status[$i] != "In Translation"){ ?>
            <tr>
                <td><?php echo $project_name[$i]; ?><br/><?php echo $date_created[$i]; ?></td>
                <td><?php echo $translate_from[$i]; ?></td>
                <td><?php echo $translate_to[$i]; ?></td>
                <td style="text-align: center">
                    <?php if($voted[$i]) echo '<span style="display: none;">1</span><i class="icon-ok"></i>';
                          else echo '<span style="display: none;">0</span><i class="icon-minus"></i>'; ?>
                </td>
                <td style="text-align: center">
                    <span style="display: none"><?php echo $good_votes[$i] - $bad_votes[$i]; ?></span>
                    <span class="badge badge-success"><?php echo $good_votes[$i]; ?></span> /
                    <span class="badge badge-important"><?php echo $bad_votes[$i]; ?></span>
                </td>
                <td style="text-align: left">
                    <?php
                        $original_text = strip_quotes(str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $translated[$i]));
                        $translated_text = strip_quotes(str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $audio_id[$i]));
                        $from = $translate_from[$i];
                        $to = $translate_to[$i];
                        if(!$voted[$i]){
                    ?>
                    <a rel="tooltip" data-placement="top" data-original-title="Review & Vote on this translation"
                       data-toggle="modal" href="#vote_modal" onclick="prepare_vote_modal('<?php echo $original_text; ?>',
                            '<?php echo $id[$i]; ?>', '<?php echo $from; ?>', '<?php echo $to; ?>', '<?php echo $audio_id[$i]; ?>');">
                        <i class="icon-check"></i> Review & Vote
                    </a><br/>
                    <?php } ?>
                    <a onclick="prepare_video_modal('<?php echo $project_video_id[$i]; ?>', '<?php echo $project_name[$i]; ?>', $('#description_<?php echo $i; ?>').val());"
                       href="#video_modal" rel="tooltip" data-placement="top" data-original-title="Open video of this project." data-toggle="modal">
                        <i class="icon-film"></i> Watch video
                    </a>
                    <textarea id="description_<?php echo $i; ?>" style="display: none;">
                        <?php echo strip_quotes(strip_slashes(trim($project_description[$i]))); ?>
                    </textarea><br/>
                    <span rel="tooltip" data-placement="top" data-original-title="Ask to vote by sharing it on Facebook.">
                        <a href="#" onclick="facebook_share('<?php echo $project_id[$i]; ?>')">
                            <img src="<?php echo base_url("template/img/extra_icons/glyphicons_410_facebook.png"); ?>"
                                 style="width: 13px; height: 13px"/> Facebook
                        </a>
                    </span><br/>
                    <span rel="tooltip" data-placement="top" data-original-title="Ask to vote by sharing it on Twitter.">
                        <a href="#" onclick="twitter_share('<?php echo $project_id[$i]; ?>')">
                            <img src="<?php echo base_url("template/img/extra_icons/glyphicons_411_twitter.png"); ?>"
                                 style="width: 13px; height: 13px"/> Twitter
                        </a>
                    </span>
                </td>
            </tr>
            <?php } } } else {?>
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
<div class="modal hide fade" id="vote_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Contribute by voting this task</h3>
    </div>
    <div class="modal-body">
        <form id="vote_modal_form" action="<?php echo base_url('admin/translate/vote'); ?>" method="post">
            <strong>Translated from <span id="vote_modal_from"></span> to <span class="vote_modal_to"></span>:</strong><br/>
            <textarea id="vote_modal_text" disabled="disabled" class="translate_text"></textarea><br/><br/>
            <strong>Audio recorded in <span class="vote_modal_to"></span>:</strong>
            <iframe id="soundcloud_player" width="100%" height="166" scrolling="no" frameborder="no" src=""></iframe>
            <input id="vote_modal_translation_id" type="hidden" name="id"/>
            <input type="hidden" name="translation_type" value="<?php echo $translation_type; ?>">
            <input id="vote_modal_vote_type" type="hidden" name="vote_type">
        </form>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
        <button id="bad_vote" type="button" class="btn btn-danger" style="width: 100px;">
            <img style="height: 16px;" class="invert"
                 src="<?php echo base_url("template/img/extra_icons/glyphicons_344_thumbs_down.png"); ?>"/> Bad
        </button>
        <button id="good_vote" type="button" class="btn btn-success" style="width: 100px;">
            <img style="height: 16px;" class="invert"
                 src="<?php echo base_url("template/img/extra_icons/glyphicons_343_thumbs_up.png"); ?>"/> Good
        </button>
    </div>
</div>
<script>
    function prepare_video_modal(video_id, project_name, project_description) {
        $("#video").attr("src", "http://www.youtube.com/embed/" + video_id);
        $("#video_modal_title").text("Video of '" + project_name + "'");
        $("#description").text(project_description);
    }

    function prepare_vote_modal(text, id, from, to, audio_id) {
        $("#vote_modal_text").val(text);
        $("#vote_modal_translation_id").val(id);
        $("#vote_modal_from").val(from);
        $("#vote_modal_to").val(to);
        url = "https://w.soundcloud.com/player/?url=http://api.soundcloud.com/tracks/" + audio_id + "&color=ff6600&auto_play=false&show_artwork=false";
        $("#soundcloud_player").attr("src", url);
    }

    function facebook_share(id) {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent('<?php echo base_url("public/vote/audio")."/"; ?>' + id),
                '', 'width=600,height=300');
    }

    function twitter_share(id) {
        window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('Please contribute by voting this translation ') +
                encodeURIComponent('<?php echo base_url("public/vote/audio")."/"; ?>' + id),
                '', 'width=600,height=300');
    }
</script>
<style>
    .translate_text {
        width: 97%;
        height: 150px;
        margin-top: 5px;
        resize: none;
    }

    .invert{
        filter: invert(100%);
        -webkit-filter: invert(100%);
        -moz-filter: invert(100%);
        -o-filter: invert(100%);
        -ms-filter: invert(100%);
    }
</style>
<?php $this->load->view('_inc/datatables'); ?>
<script>
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();

        $("#good_vote").click(function() {
            $("#vote_modal_vote_type").val("good");
            $("#vote_modal_form").submit();
        });

        $("#bad_vote").click(function() {
            $("#vote_modal_vote_type").val("bad");
            $("#vote_modal_form").submit();
        });

        $(".datatable thead tr th:nth-child(5)").click().click();
    });
</script>
<?php $this->load->view('_inc/footer_base'); ?>