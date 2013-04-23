<?php $this->load->view('_inc/header'); ?>
<?php $this->load->view('_inc/navbar'); ?>
<div class="container" style="margin-top:80px;">
<div class="row">
    <div class="span12">
    	<h3>Frequently asked questions [FAQ]</h3>
        <div class="round-border">
            <p>Some of the most frequently asked questions:</p>
            <div class="accordion" id="accordion2">
                <?php for($i = 0; $i < sizeof($question); $i++){ ?>
                <div class="accordion-group">
                    <div class="accordion-heading" style="background-color: #E6E6E6">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $i; ?>">
                            <?php echo $question[$i]; ?>
                        </a>
                    </div>
                    <div id="collapse<?php echo $i; ?>" class="accordion-body collapse in">
                        <div class="accordion-inner">
                            <?php echo $answer[$i]; ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
		</div>
    </div>
</div>
</div>
<?php $this->load->view('_inc/footer'); ?>