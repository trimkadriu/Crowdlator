<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">Create new project</h3><hr/>
    <form method="post" action="<?php echo base_url('admin/projects/create_project'); ?>" name="create_project" id="create_project">
    <div id="first">
        <div class="span5">
            <div class="control-group"><?php
                echo form_label('Project name:', 'projectname', array('class', 'control-label')); ?>
                <div class="controls"><?php
                    $input_projectname = array('type' => 'text', 'name' => 'project_name',
                                        'placeholder' => 'Your project name', 'required' => 'required',
                                        'class' => 'input-block-level');
                    echo form_input($input_projectname); ?>
                </div>
            </div>
            <div class="control-group"><?php
                echo form_label('Project description:', 'projectdescription', array('class', 'control-label')); ?>
                <div class="controls"><?php
                    $input_projectname = array('type' => 'text', 'name' => 'project_description',
                                        'required' => 'required', 'rows' => '6',
                                        'class' => 'input-block-level');
                    echo form_textarea($input_projectname); ?>
                </div>
            </div>
            <div class="control-group"><?php
                echo form_label('Translate from language:', 'translate_from_language', array('class', 'control-label')); ?>
                <div class="controls"><?php
                    $input_translate_from_language = 'required = "required" class ="input-block-level"';
                    $options = array(
                                '' => '--- Select Language ---', 'Albanian' => 'Albanian', 'Arabic' => 'Arabic',
                                'Bulgarian' => 'Bulgarian',	'Croatian' => 'Croatian', 'Czech' => 'Czech',
                                'Danish' => 'Danish', 'English' => 'English', 'French' => 'French',
                                'German' => 'German', 'Greek' => 'Greek', 'Indonesian' => 'Indonesian',
                                'Italian' => 'Italian', 'Polish' => 'Polish', 'Romanian' => 'Romanian',
                                'Russian' => 'Russian',	'Serbian' => 'Serbian',	'Slovenian' => 'Slovenian',
                                'Turkish' => 'Turkish'
                                );
                    echo form_dropdown('translate_from_language', $options, '', $input_translate_from_language); ?>
                </div>
            </div>
            <div class="control-group"><?php
                echo form_label('Translate to language:', 'translate_to_language', array('class', 'control-label')); ?>
                <div class="controls"><?php
                    $input_translate_to_language = 'required = "required" class ="input-block-level"';
                    $options = array(
                                '' => '--- Select Language ---', 'Albanian' => 'Albanian', 'Arabic' => 'Arabic',
                                'Bulgarian' => 'Bulgarian',	'Croatian' => 'Croatian', 'Czech' => 'Czech',
                                'Danish' => 'Danish', 'English' => 'English', 'French' => 'French',
                                'German' => 'German', 'Greek' => 'Greek', 'Indonesian' => 'Indonesian',
                                'Italian' => 'Italian', 'Polish' => 'Polish', 'Romanian' => 'Romanian',
                                'Russian' => 'Russian',	'Serbian' => 'Serbian',	'Slovenian' => 'Slovenian',
                                'Turkish' => 'Turkish'
                                );
                    echo form_dropdown('translate_to_language', $options, '', $input_translate_to_language); ?>
                </div>
            </div>
            <!--<div class="control-group"><?php
    /*            echo form_label('Translations for each task:', 'translations', array('class', 'control-label')); */?>
                <div class="controls"><?php
    /*                $input_translations = array('type' => 'number', 'name' => 'translations', 'min' => '1', 'max' => '5',
                                        'placeholder' => 'Number of translations', 'required' => 'required', 'value' => '1',
                                        'class' => 'input-block-level');
                    echo form_input($input_translations); */?>
                </div>
            </div>-->
        </div>
        <!-- Second column -->
        <div class="span5">
            <div class="control-group"><?php
                echo form_label('Microtasks:', 'microtasks', array('class', 'control-label')); ?>
                <div class="controls" id="parent_microtasks">
                    <label class="radio">
                        <?php echo form_radio('microtasks', 'sentences', false); ?>Break text by number of sentences.
                        <div class="sentences" style="display:none"><?php
                            $input_sentences_nr = array('type' => 'number', 'name' => 'sentences_nr',
                                        'min' => '3', 'max' => '10', 'required' => 'required', 'value' => '3',
                                        'class' => 'input-block-level', 'style' => 'margin-top:10px;');
                            echo form_input($input_sentences_nr); ?>
                        </div>
                    </label>
                    <label class="radio">
                        <?php echo form_radio('microtasks', 'paragraphs', false); ?>Break text by number of paragraphs.
                        <div class="paragraphs" style="display:none"><?php
                            $input_paragraphs_nr = array('type' => 'number', 'name' => 'paragraphs_nr',
                                        'min' => '1', 'max' => '20', 'required' => 'required', 'value' => '1',
                                        'class' => 'input-block-level', 'style' => 'margin-top:10px;');
                            echo form_input($input_paragraphs_nr); ?>
                        </div>
                    </label>
                    <!--<label class="radio">
                        <?php /*echo form_radio('microtasks', 'pages', false); */?>Break text by number of pages.
                        <div class="pages" style="display:none"><?php
    /*                        $input_pages_nr = array('type' => 'number', 'name' => 'pages_nr',
                                        'min' => '1', 'max' => '100', 'required' => 'required', 'value' => '1',
                                        'class' => 'input-block-level', 'style' => 'margin-top:10px;');
                            echo form_input($input_pages_nr); */?>
                        </div>
                    </label>-->
                    <input id="microtasks_by" name="microtasks_by" type="hidden" value="">
                    <input id="break_text" name="break_text" type="hidden" value="">
                </div>
            </div>
            <div class="control-group"><?php
                echo form_label('Upload or paste the text to be translated:', 'texttobetranslated', array('class', 'control-label')); ?>
                <div class="controls" id="parent_texttobetranslated">
                    <!--<label class="radio" style="margin-right:9px;">
                        <?php /*echo form_radio('text', 'upload', false); */?>
                        Upload text
                        <a class="badge badge-info" href="#" rel="tooltip" data-placement="top"
                            data-original-title="Supported formats of text uploading are: .TXT, .DOC, .DOCX">?</a>
                        <div class="upload" style="display:none">
                            <div class="fileupload fileupload-new" data-provides="fileupload" style="margin-top:9px;">
                                <div class="input-append">
                                    <div class="uneditable-input span3">
                                        <i class="icon-file fileupload-exists"></i><span class="fileupload-preview"></span>
                                    </div>
                                    <span class="btn btn-file">
                                        <span class="fileupload-new">Select file</span>
                                        <span class="fileupload-exists">Change</span>
                                        <input type="file" />
                                    </span>
                                    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                </div>
                            </div>
                        </div>
                    </label>-->
                    <label class="radio">
                        <?php echo form_radio('text', 'paste', false); ?>
                        Paste text
                        <div class="paste" style="display:none">
                            <?php
                                $input_pastetext = array('type' => 'text', 'name' => 'paste_text',
                                                    'required' => 'required', 'rows' => '6',
                                                    'class' => 'input-block-level', 'style' => 'margin-top:10px;');
                                echo form_textarea($input_pastetext);
                            ?>
                        </div>
                    </label>
                </div>
            </div>
            <div class="control-group"><?php
                echo form_label('Hashtags:', 'hashtags', array('class', 'control-label')); ?>
                <div class="controls"><?php
                    $input_hashtags = array('type' => 'text', 'name' => 'hashtags',
                                        'placeholder' => 'Hashtags (#hash1, #hash2 etc.)', 'required' => 'required',
                                        'class' => 'input-block-level');
                    echo form_input($input_hashtags); ?>
                </div>
            </div>
        </div>
        <div class="span10">
            <div class="control-group pull-right">
                <div class="controls">
                    <a href="<?php echo base_url('admin/user/dashboard'); ?>" class="btn">Cancel</a>
                    <button class="btn btn-primary tab" id="next_tab">NEXT ></button>
                </div>
            </div>
        </div>
    </div>
    </form>
    <div id="second">
        <div class="span11">
            <h4>Upload video:</h4><br/>
            <div class="input-append">
                <label>Select video here:</label>
                <input class="span3" id="youtube_video_filename" value="No file is selected!" type="text" style="cursor:text;" readonly>
                <button class="btn" onclick="$('#youtube_video').click();">Select file</button>
            </div>
            <div style="margin: 20px 0 20px 0;">Or</div>
            <form name="youtube_upload" id="youtube_upload" method="post" enctype="multipart/form-data">
                <div id="youtube_video_div" class="round-border youtube_video_div align-vertical">
                    <input id="youtube_video" type="file" name="file" accept="video/*"
                           style="position:absolute; z-index: 1; color: #eeeeee"/>
                    <div style="background-color: #eeeeee; position: absolute; top: 0px; width: 200px; height: 35px; z-index: 2"></div>
                    <div class="child" style="z-index: 0">
                        <span style="color: #888888" id="youtube_video_filename1">Drop video here!</span>
                    </div>
                </div>
                <input id="token" type="hidden" name="token" value=""/>
            </form>
            <div class="control-group pull-right">
                <div class="controls">
                    <a href="<?php echo base_url('admin/user/dashboard'); ?>" class="btn">Cancel</a>
                    <button class="btn btn-primary" id="upload_video" disabled>Create project</button>
                </div>
            </div>
        </div>
    </div>
    <div id="progress_bar" class="span11">
        <div class="progress progress-striped active">
            <div id="bar" class="bar" style="width: 100%;"></div>
        </div>
    </div>
</div>
<div class="modal hide fade" id="validation_error">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Validation Error!</h3>
    </div>
    <div class="modal-body">
        <p>
            This file extension is not supported. Please choose another one.
        </p>
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">OK</a>
    </div>
</div>
<script>
$(document).ready(function() {

    $('#second').hide();
    var finish = false;
    var progress_bar = $('#progress_bar');
    progress_bar.hide()
    var progress_bar_text = progress_bar.find('#bar');

    //Form handlers for selecting different options
	$("input[name='microtasks']").change(function(){
		var span_name = $("input[name='microtasks']:checked").val();
		$('#microtasks_by').val(span_name);
		$('#break_text').val($("input[name='microtasks']:checked").next().children().val());
		$('#parent_microtasks').find('div').slideUp();
		$('.' + span_name).slideDown();
	});
	$("input[name='text']").change(function(){
		var span_name = $("input[name='text']:checked").val();
		$('#parent_texttobetranslated').children().children('div').slideUp();
		$('.' + span_name).slideDown();
 	});

    //First tab click NEXT button
    $("#next_tab").click(function(){
        if(form_validate()){
            //get_youtube_token();
            $("#first").slideToggle(800);
            $("#second").slideToggle(800);
        }
    });

    //Disable form default submitting "Create Project"
    $("#create_project").submit(function(e){
        e.preventDefault();
        return false;
    });

    $("#upload_video").click(function(){
        //Create project AJAX
        $('#second').slideToggle(800);
        progress_bar.slideToggle(800);
        progress_bar_text.text('Creating project...');
        result = create_project();
        if(!result)
            window.location = "<?php echo base_url("admin/user/dashboard"); ?>";
    });

    //On video file drop
    $('#youtube_video').change(function(e){
        fullpath = $(this).val();
        filename = "No file is selected!";
        validation = false;
        $("#youtube_video_div").removeClass('youtube_video_div_dragover youtube_video_div_drop');
        if(fullpath != ""){
            filename = fullpath.substring(fullpath.lastIndexOf("\\") + 1, fullpath.length);
            extension = filename.substring(filename.lastIndexOf(".") + 1, filename.length);
            allowed_extensions = new Array("MOV", "MPEG", "AVI", "WMV", "MPEGPS", "FLV", "3GPP", "MP4");
            for(var i = 0; i < allowed_extensions.length; i++){console.log(extension);
                if(extension.toUpperCase() == allowed_extensions[i]){
                    validation = true;
                    break;
                }
                else{
                    validation = false;
                }
            }
        }
        if(validation){
            $("#youtube_video_div").addClass('youtube_video_div_drop');
            $("#upload_video").removeAttr('disabled');
        }
        else{
            $("#youtube_video_div").addClass('youtube_video_div');
            filename = "No file is selected!"
            $('#validation_error').modal('show');
        }
        $('#youtube_video_filename1').text(filename);
        $('#youtube_video_filename').val(filename);
    });

    //Video Upload for Drag & Drop
    dropZone = $("#youtube_video_div");
    dropZone[0].ondragover = function() {
        dropZone.removeClass('youtube_video_div');
        dropZone.addClass('youtube_video_div_dragover');
        return false;
    };
    dropZone[0].ondragend = function() {
        dropZone.removeClass('youtube_video_div_dragover');
        dropZone.addClass('youtube_video_div');
        return false;
    };
    dropZone[0].ondragleave = function() {
        dropZone.removeClass('youtube_video_div_dragover');
        dropZone.addClass('youtube_video_div');
        return false;
    };
});

//AJAX Function for Youtube token
function get_youtube_token(){
    progress_bar_text = $('#progress_bar').find('#bar');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url('admin/projects/upload_video'); ?>",
        dataType: 'json',
        data: {
            video_title: $('input[name=project_name]').val(),
            video_description: $('textarea[name=project_description]').val()
        },
        success: function(data){
            $('#token').val(data.youtube_response.token);
            $('#youtube_upload').attr('action', data.youtube_response.url + '?nexturl=' + encodeURIComponent(data.next_url));
            progress_bar_text.text('Uploading video...');
            $("#youtube_upload").submit();
        }
    });
}

//AJAX create project
function create_project(){
    create_project_form = $("#create_project");
    progress_bar_text = $('#progress_bar').find('#bar');
    status = false;
    $.ajax({
        type: "POST",
        url: create_project_form.attr("action"),
        dataType: 'json',
        data: {
            project_name: create_project_form.find("input[name=project_name]").val(),
            project_description: create_project_form.find("textarea[name=project_description]").val(),
            translate_from_language: create_project_form.find("select[name=translate_from_language]").val(),
            translate_to_language: create_project_form.find("select[name=translate_to_language]").val(),
            microtasks_by: create_project_form.find("input[name=microtasks_by]").val(),
            break_text: create_project_form.find("input[name=break_text]").val(),
            paste_text: create_project_form.find("textarea[name=paste_text]").val(),
            hashtags: create_project_form.find("input[name=hashtags]").val()
        },
        success: function(data){
            status = data.project_status;
            if(status){
                finish = true;
                progress_bar_text.text('Preparing video upload...');
                get_youtube_token();
            }
        }
    });
    return status;
}

//Validate if form is complete
function form_validate(){
    var status = false;
    $('div#first input[type=text]').each(function(){
        if(this.value == "" ){
            status = false;
            return false;
        }
        else
            status = true;
    });
    if(status){
        $('div#first select').each(function(){
            if(this.value == "" ){
                status = false;
                return false;
            }
        });
    }
    if(status){
        $('div#first textarea').each(function(){
            if(this.value == "" || this.value.length < 5){
                alert('Please write more on "Description" or "Text to be translated"');
                status = false;
                return false;
            }
        });
    }
    return status;
}
</script>
<style>
#youtube_video{
    height: 190px;
    width: 95%;
}
.youtube_video_div{
    height: 200px;
    width: 100%;
    padding: 5px;
    border: 3px dashed #d3d3d3;
    background-color: #eeeeee;
}
.youtube_video_div_drop{
    border: 3px solid #71F200;
    background-color: #eeeeee;
}
.youtube_video_div_dragover{
    border: 3px solid #0088cc;
    background-color: #eeeeee;
}
</style>
<?php $this->load->view('_inc/footer_base'); ?>