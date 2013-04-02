<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
        Account Settings
    </h3><hr/>
    <?php echo form_open(base_url('admin/settings/account_settings'));?>
    <div class="span6" style="margin-left:0px;">
        <div class="control-group"><?php
            echo form_label('Full name:', 'fullname', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_fullname = array('type' => 'text', 'name' => 'full_name',
                    'placeholder' => 'Your name and surname', 'required' => 'required',
                    'class' => 'input-block-level', 'value' => $user->fullname);
                echo form_input($input_fullname); ?>
            </div>
        </div>
        <div class="control-group"><?php
            echo form_label('Address:', 'address', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_address = array('type' => 'text', 'name' => 'address',
                    'placeholder' => 'Your living address',
                    'class' => 'input-block-level', 'value' => $user->address);
                echo form_input($input_address); ?>
            </div>
        </div>
        <div class="control-group"><?php
            echo form_label('City:', 'city', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_city = array('type' => 'text', 'name' => 'city',
                    'placeholder' => 'Your city', 'class' => 'input-block-level',
                    'value' => $user->city);
                echo form_input($input_city); ?>
            </div>
        </div>
        <?php get_html_country_dropdown_list(); ?>
        <script>$("select[name='country']").val('<?php echo $user->country; ?>')</script>
    </div>
    <div class="span6">
        <div class="control-group"><?php
            echo form_label('Username:', 'username', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_username = array('type' => 'text', 'disabled' => 'disabled',
                    'placeholder' => 'Your username',
                    'class' => 'input-block-level', 'value' => $user->username);
                echo form_input($input_username); ?>
            </div>
        </div>
        <div class="control-group"><?php
            $new_password_info = '<a class="badge badge-info" href="#" rel="tooltip" data-placement="right"
               data-original-title="You can leave this field blank if you want to keep your old password.">?</a>';
            echo form_label('New password: '.$new_password_info, 'password', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_password = array('type' => 'password', 'name' => 'password', 'class' => 'input-block-level');
                echo form_input($input_password); ?>
            </div>
        </div>
        <div class="control-group"><?php
            echo form_label('Confirm new password: '.$new_password_info, 'confirm_password', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_confirm_password = array('type' => 'password', 'name' => 'confirm_password', 'class' => 'input-block-level');
                echo form_input($input_confirm_password); ?>
            </div>
        </div>
        <div class="control-group"><?php
            echo form_label('Email:', 'email', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_email = array('type' => 'text', 'name' => 'email',
                    'placeholder' => 'Your email', 'required' => 'required',
                    'class' => 'input-block-level', 'value' => $user->email);
                echo form_input($input_email); ?>
            </div>
        </div>
        <div class="control-group pull-right">
            <div class="controls">
                <button type="submit" class="btn btn-primary" name="register">Update</button>
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
<script>
$(document).ready(function(){
    $("[rel=tooltip]").tooltip();
})
</script>
<?php $this->load->view('_inc/footer'); ?>