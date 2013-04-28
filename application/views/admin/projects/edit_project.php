<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px;">Edit details for "<?php echo $translation->project_name; ?>" project</h3><hr/>
    <?php echo form_open(base_url('admin/projects/edit_project/'));?>
    <div class="span5">
        <div class="control-group"><?php
            echo form_label('Project name:', 'projectname', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_projectname = array('type' => 'text', 'name' => 'project_name', 
                                    'placeholder' => 'Your project name', 'required' => 'required',
                                    'class' => 'input-block-level', 'value' => $translation->project_name);
                echo form_input($input_projectname); ?>
            </div>
        </div>
        
        <div class="control-group"><?php
            echo form_label('Project description:', 'projectdescription', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_projectname = array('type' => 'text', 'name' => 'project_description', 
                                    'required' => 'required', 'rows' => '6',
                                    'class' => 'input-block-level', 'value' => $translation->project_description);
                echo form_textarea($input_projectname); ?>
            </div>
        </div>

        <div class="control-group"><?php
            echo form_label('Hashtags:', 'hashtags', array('class', 'control-label')); ?>
            <div class="controls"><?php
                $input_hashtags = array('type' => 'text', 'name' => 'hashtags',
                    'placeholder' => 'Hashtags (#hash1, #hash2 etc.)', 'required' => 'required',
                    'class' => 'input-block-level', 'value' => $translation->hash_tags);
                echo form_input($input_hashtags); ?>
            </div>
        </div>

        <div class="control-group pull-right">
            <div class="controls">
                <button type="button" onclick="history.back();" class="btn">Back</button>
                <button type="submit" class="btn btn-primary" id="submit">Update Project</button>
            </div>
        </div>
        <input type="hidden" name="project_id" value="<?php echo $translation->id; ?>"
    <?php form_close(); ?>
    </div>
</div>
<?php $this->load->view('_inc/footer_base'); ?>