<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
    	Contribute translating for "<?php echo $project_name; ?>" project
	</h3><hr/>
    <div style="margin:20px;" class="span6">
        <strong>Translated from <?php echo $translate_from; ?> to <?php echo $translate_to; ?>:</strong><br/>
        <textarea disabled="disabled" class="translate_text"><?php echo $translated_text; ?></textarea><br/><br/>
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
        <strong>Record audio in <?php echo $translate_to; ?>:</strong><br/>
        <input type="hidden" name="project_id" value="<?php echo $project_id; ?>"/>
        <span class="ctrl-btn-container" style="margin: 0 0 20px 0; width: 95%;">
            <a href="#" id="startRecording" rel="tooltip" data-placement="bottom" data-original-title="Start recording">
                <span class="ctrl-btn rec"></span>
            </a>
            <a href="#" id="stopRecording" rel="tooltip" data-placement="bottom" data-original-title="Stop recording">
                <span class="ctrl-btn stop"></span>
            </a>
            <a href="#" id="playBack" rel="tooltip" data-placement="bottom" data-original-title="Play audio">
                <span class="ctrl-btn play"></span>
            </a>
            <a href="#" id="pause" rel="tooltip" data-placement="bottom" data-original-title="Pause audio">
                <span class="ctrl-btn pause"></span>
            </a>
            <!--<a href="#" id="upload" rel="tooltip" data-placement="bottom" data-original-title="UPLOAD">
                <span class="sc-upload"></span>
            </a>-->
            <p class="status">
                <span style="border-bottom: 1px solid #d9d9d9;">Recording time</span>
                <span class="time">0.00</span>
            </p>
            <hr/>
            <div style="display: inline-block; width: 100%; text-align: right; margin: 0 0 10px 0;">
                <div class="progress progress-striped active" id="upload_progress" style="display: none;">
                    <div class="bar" style="width: 100%;">Uploading...</div>
                </div>
                <button class="btn btn-danger" id="reset_audio">New Record</button>
                <button class="btn btn-success" disabled="disabled" id="upload_button">Upload Audio</button>
            </div>
            <strong>Note: </strong>This video duration is <?php echo $video_duration; ?>s. Please note that every second more than the video duration will be truncated.
        </span>
    </div>
    <div class="span5">
        <h4>Project details</h4><hr/>
        <iframe width="380" height="214" src="http://www.youtube.com/embed/<?php echo $project_video_id; ?>" frameborder="0" allowfullscreen></iframe>
        <p>
            <span style="text-decoration: underline; font-weight: bold">Description:</span>
            <?php echo $project_description; ?>
        </p>
        <p>
            <span style="text-decoration: underline; font-weight: bold; margin-bottom: 5px">Social Networks:</span>
        </p>
        <div style="text-align: center">
            <span rel="tooltip" data-placement="top" data-original-title="Ask for audio recording by sharing it on Facebook.">
                <a href="#" onclick="facebook_share()">
                    <img src="<?php echo base_url("template/img/extra_icons/glyphicons_410_facebook.png"); ?>"
                         style="width: 28px; height: 28px"/>
                </a>
            </span>
            <span rel="tooltip" data-placement="top" data-original-title="Ask for audio recording by sharing it on Twitter.">
                <a href="#" onclick="twitter_share()">
                    <img src="<?php echo base_url("template/img/extra_icons/glyphicons_411_twitter.png"); ?>"
                         style="width: 28px; height: 28px"/>
                </a>
            </span>
        </div>
    </div>
</div>
<div class="modal hide fade" id="entry_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Information</h3>
    </div>
    <div class="modal-body">
        <p><span style="font-weight: bold;" class="label label-warning">Warning:</span>
            Please make sure to record the audio accordingly with the video to avoid non-synchronized timing.</p>
        <p>We strongly recommend to watch the video first.</p>
    </div>
    <div class="modal-footer">
        <a class="btn btn-primary" data-dismiss="modal">Done</a>
    </div>
</div>
<div class="modal hide fade" id="error_message">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Error</h3>
    </div>
    <div class="modal-body">
        <p>
            <span style="font-weight: bold;" class="label label-important">Error:</span>
            Captcha code is invalid please try again
        </p>
    </div>
    <div class="modal-footer">
        <a class="btn btn-primary" data-dismiss="modal">Done</a>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();
        $("#entry_modal").modal("show");
        $('#playBack').hide();
        $('#upload').hide();
        $('#pause').hide();
        $("#reset_audio").hide()

        $('#startRecording').click(function(e) {
            e.preventDefault();
            SC.record({
                progress: function(ms, avgPeak) {
                    updateTimer(ms);
                }
            });
            $("#reset_audio").attr("disabled", "disabled").hide();
        });

        $('#stopRecording').click(function(e) {
            e.preventDefault();
            SC.recordStop();
            if($('.status .time').text() != "0.00") {
                $("#startRecording").hide();
                $('#playBack').show();
                $("#upload_button").removeAttr("disabled");
                $("#reset_audio").removeAttr("disabled").show();
            }
        });

        $("#reset_audio").click(function(e) {
            e.preventDefault();
            $('#playBack').hide();
            $('#startRecording').show();
            $("#upload_button").attr("disabled", "disabled");
            $('.status .time').text("0.00");
            $(this).hide();
        });

        $('#playBack').click(function(e) {
            e.preventDefault();
            updateTimer(0);
            SC.recordPlay({
                progress: function(ms) {
                    updateTimer(ms);
                }
            });
        });

        $('#upload_button').click(function(e) {
            e.preventDefault();
            $(this).text("Validating...").attr("disabled", "disabled");
            $("#reset_audio").hide();
            $.post(
                "<?php echo base_url("public/translate/validate_audio_captcha"); ?>", {
                    captcha_code: $("input[name=captcha_code]").val()
                },
                function(data){
                    var obj = $.parseJSON(data);
                    if(obj.return_result == true) {
                        $("#upload_button").text("Please wait...");
                        $("#upload_progress").show()
                        soundcloud_upload();
                    }
                    else if(obj.return_result == false) {
                        //Recaptcha.reload();
                        $("#reset_audio").show();
                        $("#upload_button").text("Upload Audio").removeAttr("disabled");
                        $("#error_message").modal("show");
                    }
                }
            )
        });
    });

    function soundcloud_upload() {
        SC.accessToken('<?php if(isset($access_token)) echo $access_token; ?>');
        SC.recordUpload({
            track: {
                title: '<?php echo preg_replace("/[\s]/", "-", trim($project_name))."-".generateRandomString(5); ?>',
                sharing: 'public',
                downloadable: true
            }
        }, function(track) {
            $("#upload_progress").hide();
            $.post("<?php echo base_url('admin/translate/audio_audition') ?>", {
                        public: $("input[name=public]").val(),
                        project_id: $("input[name=project_id]").val(),
                        audio_id: track.id,
                        permalink_url: track.permalink_url,
                        download_url: track.download_url
                    },
                    function(data){
                        var obj = $.parseJSON(data);
                        window.location.href = obj.return_url;
                    }
            );
            //$('.status').html("Uploaded: <a href='" + track.permalink_url + "'>" + track.permalink_url + "</a>");
        });
    }

    // Helper methods for our UI.
    function updateTimer(ms) {
        $('.status .time').text(SC.Helper.millisecondsToHMS(ms));
    }

    function facebook_share() {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href),
                '', 'width=600,height=300');
    }

    function twitter_share() {
        window.open('https://twitter.com/intent/tweet?text=' +
                encodeURIComponent('Please contribute by recording audio for this translated text <?php echo $twitter_hash_tag; ?> ') +
                encodeURIComponent(window.location.href),
                '', 'width=600,height=300');
    }
</script>
<style>
    .translate_text {width: 95%; height: 150px; margin-top: 5px; resize: none;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('template/css/soundcloud.css'); ?>"/>
<script src="//connect.soundcloud.com/sdk.js"></script>
<?php $this->load->view('_inc/footer_base'); ?>