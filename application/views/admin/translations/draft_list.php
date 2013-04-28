<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
        List of my drafts
    </h3><hr/>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
            <tr>
                <th style="width:35%;">Project name</th>
                <th id="date" style="width:20%;">Date saved</th>
                <th style="width:15%;">From</th>
                <th style="width:15%;">To</th>
                <th style="width:10%; text-align: center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if($drafts_nr > 0) { for($i = 0; $i < $drafts_nr; $i++) { ?>
            <tr>
                <td><?php echo $project_name[$i]; ?></td>
                <td><?php echo $date_saved[$i]; ?></td>
                <td><?php echo $translated_from[$i]; ?></td>
                <td><?php echo $translated_to[$i]; ?></td>
                <td style="text-align: center">
                    <a rel="tooltip" data-placement="top" data-original-title="Continue translating this draft"
                        href="<?php echo base_url('admin/translate/drafts/'.$draft_id[$i]); ?>">
                        <i class="icon-zoom-in"></i>
                    </a>
                    <a rel="tooltip" data-placement="top" data-original-title="Remove draft" data-toggle="modal"
                       onclick="prepare_delete_confirm(<?php echo $draft_id[$i]; ?>);" href="#delete_confirm">
                        <i class="icon-remove"></i>
                    </a>
                </td>
            </tr>
                <?php } } else {?>
            <tr><td colspan="6">No drafts found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="modal hide fade" id="delete_confirm">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Confirm delete ?</h3>
    </div>
    <div class="modal-body">
        <p>Are you sure you want to delete this draft ?</p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">No</a>
        <a class="btn btn-primary" id="delete_confirmed">Yes</a>
    </div>
</div>
<input type="hidden" id="row_id" value=""/>
<?php $this->load->view('_inc/datatables'); ?>
<?php $this->load->view('_inc/footer_base'); ?>
<script>
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();
        $("th#date").click().click();
    });

    function prepare_delete_confirm(draft_id) {
        $("#delete_confirmed").attr("href", "<?php echo base_url('admin/translate/delete_draft').'/'; ?>" + draft_id);
    }
</script>