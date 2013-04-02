<?php $this->load->view('_inc/header'); ?>
<div class="container" style="margin-top:80px;">
 	<h3>Register as translator</h3>
    <?php echo form_open(base_url().'register/user');?>
        <div class="span6" style="margin-left:0px;">
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
				echo form_label('Address:', 'address', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_address = array('type' => 'text', 'name' => 'address', 
										'placeholder' => 'Your living address',
										'class' => 'input-block-level');
					echo form_input($input_address); ?>
				</div>
			</div>
            <div class="control-group"><?php
				echo form_label('City:', 'city', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_city = array('type' => 'text', 'name' => 'city', 
										'placeholder' => 'Your city',
										'class' => 'input-block-level');
					echo form_input($input_city); ?>
				</div>
			</div>
            <?php echo get_html_country_dropdown_list(); ?>
		</div>
        <div class="span6">
            <div class="control-group"><?php
				echo form_label('Username:', 'username', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_username = array('type' => 'text', 'name' => 'username', 
										'placeholder' => 'Your username', 'required' => 'required',
										'class' => 'input-block-level');
					echo form_input($input_username); ?>
				</div>
			</div>
            <div class="control-group"><?php
				echo form_label('Password:', 'password', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_password = array('type' => 'password', 'name' => 'password', 
										'required' => 'required',
										'class' => 'input-block-level');
					echo form_input($input_password); ?>
				</div>
			</div>
            <div class="control-group"><?php
				echo form_label('Confirm password:', 'confirm_password', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_confirm_password = array('type' => 'password', 'name' => 'confirm_password', 
										'required' => 'required',
										'class' => 'input-block-level');
					echo form_input($input_confirm_password); ?>
				</div>
			</div>
            <div class="control-group"><?php
				echo form_label('Email:', 'email', array('class', 'control-label')); ?>
                <div class="controls"><?php
					$input_email = array('type' => 'text', 'name' => 'email', 
										'placeholder' => 'Your email', 'required' => 'required',
										'class' => 'input-block-level');
					echo form_input($input_email); ?>
				</div>
			</div>
            <div class="control-group pull-right">
                <div class="controls">
                    <button type="submit" class="btn btn-primary" name="register">Register</button>
				</div>
			</div>
            <div class="control-group pull-right" style="margin-right:10px;">
                <div class="controls">
                    <button type="reset" class="btn" id="reset">Reset</button>
                </div>
            </div>
		</div>
		<?php echo form_close(); ?>
</div>
<?php $this->load->view('_inc/footer'); ?>