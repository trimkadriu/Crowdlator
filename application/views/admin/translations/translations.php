<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
        List of translations for my tasks
    </h3><hr/>
    <ul class="nav nav-tabs">
        <li <?php if($make_active == 1) echo 'class="active"'; ?>>
            <a href="<?php echo base_url("admin/translate/translations"); ?>">All translations</a>
        </li>
        <li <?php if($make_active == 2) echo 'class="active"'; ?>>
            <a href="<?php echo base_url("admin/translate/translations_reviewed/1"); ?>">Reviewed</a>
        </li>
        <li <?php if($make_active == 3) echo 'class="active"'; ?>>
            <a href="<?php echo base_url("admin/translate/translations_reviewed/0"); ?>">Not Reviewed</a>
        </li>
        <li <?php if($make_active == 4) echo 'class="active"'; ?>>
            <a href="<?php echo base_url("admin/translate/translations_approved/1"); ?>">Approved</a>
        </li>
        <li <?php if($make_active == 5) echo 'class="active"'; ?>>
            <a href="<?php echo base_url("admin/translate/translations_approved/0"); ?>">Not Approved</a>
        </li>
    </ul>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
            <tr>
                <!--<th style="width:5%;">Task ID</th>-->
                <th style="width:20%;">Project name</th>
                <th style="width:17%;">Date translated</th>
                <th style="width:15%;">From</th>
                <th style="width:15%;">To</th>
                <th style="width:8%;">Reviewed</th>
                <th style="width:10%">Approved</th>
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
                    <?php
                    if($reviewed[$i]){
                        echo '<span style="display: none">1</span><i class="icon-ok"></i>';
                    }
                    else{
                        echo '<span style="display: none">0</span><i class="icon-minus"></i>';
                    }
                    ?>
                </td>
                <td style="text-align: center">
                    <?php
                    if($approved[$i]){
                        echo '<span style="display: none">1</span><i class="icon-ok"></i>';
                    }
                    else{
                        echo '<span style="display: none">0</span><i class="icon-minus"></i>';
                    }
                    ?>
                </td>
                <td style="text-align: center">
                    <a onclick="prepare_modal($(this).next().next().val(), $(this).next().next().next().val(),
                            '<?php echo $translation_id[$i]; ?>', '<?php echo $approved[$i]; ?>');"
                        rel="tooltip" data-placement="top" data-original-title="Review translation"
                        href="#translation_detail_modal" data-toggle="modal">
                        <i class="icon-eye-open"></i>
                    </a>
                    <textarea style="display: none;"><?php echo $translate_text[$i]; ?></textarea>
                    <textarea style="display: none;"><?php echo $translated_text[$i]; ?></textarea>
                    <a onclick="prepare_confirm_delete('<?php echo $translation_id[$i]; ?>');"
                        rel="tooltip" data-placement="top" data-original-title="Remove translation"
                        href="#confirm_delete_modal" data-toggle="modal">
                        <i class="icon-remove"></i>
                    </a>
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
        <form action="<?php echo base_url("admin/translate/editor_edit_translation"); ?>" method="post">
            <strong>Original text:</strong><br/>
            <textarea id="original" disabled="disabled" style="width: 97%; height: 100px; margin-top: 5px; resize: none;"></textarea><br/><br/>
            <strong>Translated text:</strong><br/>
            <textarea name="translated" id="translated" disabled="disabled" style="width: 97%; height: 100px; margin-top: 5px; resize: none;"></textarea>
            <input style="display: none" id="submit_edit" type="submit" value="Save" class="btn btn-primary pull-right">
            <input type="hidden" name="translation_id" value=""/>
        </form>
        <span style="display: inline-block; width: 40%;">
            <label>Reviewed: <input type="checkbox" id="reviewed" style="margin-top: -3px;"></label>
        </span>
        <span style="display: inline-block; width: 40%;">
            <label>Approved: <input type="checkbox" id="approved" style="margin-top: -3px;"></label>
        </span>
        <button class="btn btn-primary pull-right"
                onclick="$('#submit_edit').show(); $('#translated').removeAttr('disabled'); $(this).remove();">
            Edit
        </button>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal" onclick="if(changed_something) document.location.reload(true);">Close</a>
    </div>
</div>
<div class="modal hide fade" id="confirm_delete_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Confirm</h3>
    </div>
    <div class="modal-body">
        Do you really want to remove this translation ?<br/>
        <strong>Translation ID: <span id="translation_id_modal"></span></strong>
        <form id="remove_translation_form" action="<?php echo base_url("admin/translate/remove_translation"); ?>" method="post" style="display: none">
            <input type="hidden" name="translation_id"/>
        </form>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
        <a class="btn btn-danger" onclick="$('#remove_translation_form').submit();">Remove</a>
    </div>
</div>
<script>
    changed_something = false;
    function prepare_modal(text, translated, translation_id, approved) {
        if(approved == 1)
            approved = true;
        else
            approved = false;
        $('#original').val(text);
        $('#translated').val(translated);
        $('input[name=translation_id]').val(translation_id);
        $("#reviewed").attr("checked", "true");
        $("#approved").attr("checked", approved);
        change_reviewed(1, translation_id);
    }

    function prepare_confirm_delete(translation_id) {
        $("#translation_id_modal").text(translation_id);
        $("#remove_translation_form").find("input").val(translation_id);
    }

    function change_reviewed(reviewed, id){
        $.get("<?php echo base_url("admin/translate/set_reviewed?r="); ?>" + reviewed + "&id=" + id);
    }

    function change_approved(approved, id){
        $.get("<?php echo base_url("admin/translate/set_approved?a="); ?>" + approved + "&id=" + id);
    }

    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();

        $("#reviewed").change(function (){
            changed_something = true;
            reviewed = $(this).prop("checked");
            reviewed = +reviewed;
            id = $("input[name=translation_id]").val();
            change_reviewed(reviewed, id);
        });

        $("#approved").change(function (){
            changed_something = true;
            approved = $(this).prop("checked");
            approved = +approved;
            id = $("input[name=translation_id]").val();
            change_approved(approved, id);
        });
    });
</script>
<?php $this->load->view('_inc/datatables'); ?>
<?php $this->load->view('_inc/footer_base'); ?>