<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
        List of translations for my tasks
    </h3><hr/>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
            <tr>
                <!--<th style="width:5%;">Task ID</th>-->
                <th style="width:35%;">Project name</th>
                <th style="width:20%;">Date translated</th>
                <th style="width:15%;">From</th>
                <th style="width:15%;">To</th>
                <th style="width:10%; text-align: center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if($translations_nr > 0) { for($i = 0; $i < $translations_nr; $i++) { ?>
            <tr>
                <!--<td><?php /*echo $task_id[$i]; */?></td>-->
                <td><?php echo $project_name[$i]; ?></td>
                <td><?php echo $date_translated[$i]; ?></td>
                <td><?php echo $translate_from[$i]; ?></td>
                <td><?php echo $translate_to[$i]; ?></td>
                <td style="text-align: center">
                    <a onclick="prepare_modal($(this).next().next().val(), $(this).next().next().next().val());"
                      rel="tooltip" data-placement="top" data-original-title="View translation"
                      href="#translation_detail_modal" data-toggle="modal">
                        <i class="icon-zoom-in"></i>
                    </a>
                    <textarea style="display: none;"><?php echo $translate_text[$i]; ?></textarea>
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
        <h3>Translations</h3>
    </div>
    <div class="modal-body">
        <strong>Original text:</strong><br/>
        <textarea id="original" disabled="disabled" style="width: 97%; height: 100px; margin-top: 5px; resize: none;"></textarea><br/><br/>
        <strong>Translated text:</strong><br/>
        <textarea id="translated" disabled="disabled" style="width: 97%; height: 100px; margin-top: 5px; resize: none;"></textarea>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
    </div>
</div>
<script>
    function prepare_modal(text, translated) {
        $('#original').val(text);
        $('#translated').val(translated);
    }
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();
    });
</script>
<?php $this->load->view('_inc/datatables'); ?>
<?php $this->load->view('_inc/footer_base'); ?>