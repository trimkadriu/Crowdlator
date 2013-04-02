<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
    	Contribute translating for "<?php echo $project_name; ?>" project
	</h3><hr/>
    <div style="margin:20px;" class="span8">
        <form action="<?php echo base_url("admin/translate/task_id"); ?>" method="post">
            <strong>Translate from <?php echo $translate_from; ?>:</strong><br/>
            <textarea disabled="disabled" class="translate_text"><?php echo $text; ?></textarea><br/><br/>
            <strong>Translate to <?php echo $translate_to; ?>:</strong><br/>
            <textarea name="translated" style="width: 100%; height: 150px; margin-top: 5px; resize: none;"></textarea>
            <input type="hidden" name="task_id" value="<?php echo $task_id; ?>"/>
            <input type="button" class="btn btn-danger" value="Cancel" onclick="history.back()"/>
            <div class="pull-right">
                <input type="button" class="btn btn-info" value="Save to draft"/>
                <input type="submit" class="btn btn-primary" value="Translate"/>
            </div>
        </form>
    </div>
</div>
<style>
.translate_text {width: 100%; height: 150px; margin-top: 5px; resize: none;}
</style>
<?php $this->load->view('_inc/footer_base'); ?>