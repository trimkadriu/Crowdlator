<?php $this->load->view('_inc/header'); ?>
<?php $this->load->view('_inc/navbar'); ?>
<div class="container" style="margin-top:80px;">
<div>
 	<h3>Give us your feedback</h3>
    <div class="span6" style="margin-left:0px;">
		<?php echo form_open(base_url('contact/email'));?>
        	<div class="control-group"><?php
				echo form_label('Full name:', 'fullname', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_fullname = array('type' => 'text', 'name' => 'full_name', 
										'placeholder' => 'Your name and surname', 'required' => 'required',
										'class' => 'input-block-level');
					echo form_input($input_fullname); ?>
				</div>
			</div>
            
            <div class="control-group"><?php
				echo form_label('Email:', 'email', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_email = array('type' => 'text', 'name' => 'email', 
										'placeholder' => 'Your email address', 'required' => 'required',
										'class' => 'input-block-level');
					echo form_input($input_email); ?>
				</div>
			</div>
            
        	<div class="control-group"><?php
				echo form_label('Subject:', 'subject', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_subject = array('type' => 'text', 'name' => 'subject', 
										'placeholder' => 'Your subject', 'required' => 'required',
										'class' => 'input-block-level');
					echo form_input($input_subject); ?>
				</div>
			</div>
            
        	<div class="control-group"><?php
				echo form_label('Message:', 'message', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_subject = array('type' => 'textarea', 'name' => 'message', 'rows' => '6',
										'placeholder' => 'Your message', 'required' => 'required',
										'class' => 'input-block-level');
					echo form_textarea($input_subject); ?>
				</div>
			</div>
            
        	<div class="control-group">
                <div class="controls">
                	<button type="submit" class="btn btn-primary" id="submit">Send</button>
                </div>
            </div>
		<?php echo form_close(); ?>
    </div>
    <div class="span6">
    	<div class="well">
        	<iframe width="100%" height="340" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=new+york+city&aq=&sll=37.6,-95.665&sspn=60.867771,135.263672&t=m&ie=UTF8&hq=&hnear=New+York&z=11&iwloc=A&output=embed">Your browser does not support iframes</iframe>
        </div>
    </div>
</div>
</div>
<?php $this->load->view('_inc/footer'); ?>