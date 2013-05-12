<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
        List of my audio records
    </h3><hr/>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
            <tr>
                <th style="width:5%;">ID</th>
                <th style="width:30%;">Project name</th>
                <th style="width:20%;">Date created</th>
                <th style="width:15%;">From</th>
                <th style="width:15%;">To</th>
                <th style="width:15%; text-align: left">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if($audios > 0) { for($i = 0; $i < $audios; $i++) { ?>
            <tr>
                <td><?php echo $id[$i]; ?></td>
                <td><?php echo $project_name[$i]; ?></td>
                <td><?php echo $date_created[$i]; ?></td>
                <td><?php echo $translated_from[$i]; ?></td>
                <td><?php echo $translated_to[$i]; ?></td>
                <td style="text-align: left">
                    <a onclick="prepare_modal($(this).next().next().val(), '<?php echo $audio_id[$i]; ?>');"
                      rel="tooltip" data-placement="top" data-original-title="View translation"
                      href="#translation_detail_modal" data-toggle="modal">
                        <i class="icon-play-circle"></i> Review audio
                    </a>
                    <textarea style="display: none;"><?php echo $translated_text[$i]; ?></textarea>
                </td>
            </tr>
                <?php } } else {?>
            <tr><td colspan="6">No translations found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<input type="hidden" id="row_id" value=""/>
<div class="modal hide fade" id="translation_detail_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Audio</h3>
    </div>
    <div class="modal-body">
        <strong>Translated text:</strong><br/>
        <textarea id="original" disabled="disabled" style="width: 97%; height: 100px; margin-top: 5px; resize: none;"></textarea><br/><br/>
        <iframe id="soundcloud_player" width="100%" height="166" scrolling="no" frameborder="no" src=""></iframe>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
    </div>
</div>
<?php $this->load->view('_inc/datatables'); ?>
<?php $this->load->view('_inc/footer_base'); ?>
<script>
    function prepare_modal(translated, audio_id) {
        $('#original').val(translated);
        url = "https://w.soundcloud.com/player/?url=http://api.soundcloud.com/tracks/" + audio_id + "&color=ff6600&auto_play=false&show_artwork=false";
        $("#soundcloud_player").attr("src", url);
    }

    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();

        $(".datatable thead tr th:nth-child(3)").click().click();
    });
</script>