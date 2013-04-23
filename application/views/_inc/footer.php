<div class="container" style="margin-top:30px;">
    <!-- FOOTER -->
    <hr />
    <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>Copyright &copy; <?php echo date('Y').' '.$this->config->item("application_issuer").', '.
            $this->config->item("application_name").' · '; ?>
        	<a href="#privacy_modal" data-toggle="modal">Privacy</a> ·
            <a href="#terms_modal" data-toggle="modal">Terms</a>
		</p>
    </footer>
    <div class="modal hide fade" id="privacy_modal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Privacy Policy</h3>
        </div>
        <div class="modal-body">
            <?php echo $this->config->item("privacy_policy"); ?>
        </div>
        <div class="modal-footer">
            <a class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>
    <div class="modal hide fade" id="terms_modal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Terms & Conditions</h3>
        </div>
        <div class="modal-body">
            <?php echo $this->config->item("terms_conditions"); ?>
        </div>
        <div class="modal-footer">
            <a class="btn" data-dismiss="modal">Close</a>
        </div>
    </div>
</div>
<?php $this->load->view('_inc/footer_base'); ?>