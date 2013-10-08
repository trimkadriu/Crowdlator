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
            <textarea name="translated" style="width: 100%; height: 150px; margin-top: 5px; resize: none;" required=""><?php if(isset($draft_text)) echo $draft_text; ?></textarea>
            <input type="hidden" name="task_id" value="<?php echo $task_id; ?>"/>
            <input type="hidden" name="draft_id" value="<?php if(isset($draft_id)) echo $draft_id; ?>"/>
            <!-- reCaptcha CODE -->
            <!--<script type="text/javascript">
                var RecaptchaOptions = {theme : 'clean'};
            </script>
            <div style="margin-bottom: 15px;">
                <script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=<?php /*echo $recaptcha_public_key; */?>">
                </script>
                <noscript>
                    <iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php /*echo $recaptcha_public_key; */?>"
                            height="200" width="500" frameborder="0"></iframe><br>
                    <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                    <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
                </noscript>
            </div>-->
            <!-- reCaptcha CODE -->
            <div style="margin: 10px 0 15px 0;">
                <strong>Captcha code:</strong><br/>
                <input type="text" name="captcha_code" style="margin-top: 10px; width: 120px;" required>
                <?php echo $captcha; ?>
            </div>
            <input type="button" class="btn btn-danger" value="Cancel" onclick="history.back()"/>
            <div class="pull-right">
                <input id="draft" type="button" class="btn btn-info" value="<?php if(isset($draft_id)) echo 'Update draft'; else echo 'Save to draft'; ?>"/>
                <input type="submit" class="btn btn-primary" value="Translate"/>
            </div>
        </form>
    </div>
</div>
<script>
    $("#draft").click(function() {
        $("form").attr("action", "<?php echo base_url('admin/translate/drafts') ?>");
        $("form").submit();
    });
</script>
<style>
    .translate_text {width: 100%; height: 150px; margin-top: 5px; resize: none;}
</style>
<?php $this->load->view('_inc/footer_base'); ?>