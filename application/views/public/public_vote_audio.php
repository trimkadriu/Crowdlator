<?php $this->load->view('_inc/header'); ?>
<?php $this->load->view('_inc/navbar'); ?>
<div class="container" style="margin-top:80px;" xmlns="http://www.w3.org/1999/html">
    <div>
        <h3>Contribute by voting this audio</h3>
        <div class="span6" style="margin-left:0px;">
            <strong>Tranlated from <?php echo $translate_from; ?> to <?php echo $translate_to; ?>:</strong><br/>
            <textarea disabled="disabled" class="translate_text" style="width: 95%; height: 150px; margin-top: 5px;"><?php echo $text; ?></textarea><br/><br/>
            <strong>Audio recorded in <?php echo $translate_to; ?>:</strong><br/>
            <iframe id="soundcloud_player" width="100%" height="166" scrolling="no" frameborder="no"
                    src="https://w.soundcloud.com/player/?url=http://api.soundcloud.com/tracks/<?php echo $audio_id; ?>&color=ff6600&auto_play=false&show_artwork=false" style="margin: 0 0 20px 0"></iframe>
            <form action="<?php echo base_url('admin/translate/vote'); ?>" method="post" id="vote_form">
                <input type="hidden" name="id" value="<?php echo $translation_id; ?>"/>
                <input type="hidden" name="translation_type" value="<?php echo $translation_type; ?>" />
                <input id="vote_type" type="hidden" name="vote_type" />
                <!-- reCaptcha CODE -->
                <script type="text/javascript">
                    var RecaptchaOptions = {theme : 'clean'};
                </script>
                <script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=<?php echo $recaptcha_public_key; ?>">
                </script>
                <noscript>
                    <iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php echo $recaptcha_public_key; ?>"
                            height="200" width="500" frameborder="0"></iframe><br>
                    <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                    <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
                </noscript>
                <!-- reCaptcha CODE -->
            </form>
            <div style="text-align: center; margin-bottom: 20px;">
                <button id="bad_vote" type="button" class="btn btn-danger" style="width: 45%; margin: 0 5px 0 -5px">
                    <img style="height: 16px;" class="invert"
                         src="<?php echo base_url("template/img/extra_icons/glyphicons_344_thumbs_down.png"); ?>"/> Bad
                </button>
                <button id="good_vote" type="button" class="btn btn-success" style="width: 45%">
                    <img style="height: 16px;" class="invert"
                         src="<?php echo base_url("template/img/extra_icons/glyphicons_343_thumbs_up.png"); ?>"/> Good
                </button>
            </div>
        </div>
        <div class="span6" style="text-align: center;">
            <div class="well">
                <h3 style="margin:0px">Video of this project</h3><hr style="margin: 0 0 10px 0"/>
                <iframe width="420" height="236" src="http://www.youtube.com/embed/<?php echo $project_video_id; ?>" frameborder="0" allowfullscreen></iframe>
                <p>
                    <span style="text-decoration: underline; font-weight: bold">Description:</span>
                    <?php echo $project_description; ?>
                </p>
                <p>
                    <span style="text-decoration: underline; font-weight: bold; margin-bottom: 5px">Social Networks:</span>
                </p>
                <span rel="tooltip" data-placement="top" data-original-title="Ask to vote by sharing it on Facebook.">
                    <a href="#" onclick="facebook_share()">
                        <img src="<?php echo base_url("template/img/extra_icons/glyphicons_410_facebook.png"); ?>"
                             style="width: 28px; height: 28px"/>
                    </a>
                </span>
                <span rel="tooltip" data-placement="top" data-original-title="Ask to vote by sharing it on Twitter.">
                    <a href="#" onclick="twitter_share()">
                        <img src="<?php echo base_url("template/img/extra_icons/glyphicons_411_twitter.png"); ?>"
                             style="width: 28px; height: 28px"/>
                    </a>
                </span>
            </div>
        </div>
    </div>
    <div class="round-border" style="display: inline-block; padding: 10px; width: 98%;
                                        background-color: rgba(83,139,255,0.32)">
        <div class="span8" style="text-align: center">
            <h1>Register as a translator in Crowdlator for best experiences!</h1>
        </div>
        <div class="span3">
            <button class="btn btn-success btn-large" style="margin: 30px 0 0 30px"
                    onclick="change_location('<?php echo base_url('pages/register'); ?>')">
                REGISTER HERE
            </button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();

        $("#bad_vote").click(function() {
            $("#vote_type").val("bad");
            $("#vote_form").submit();
        });

        $("#good_vote").click(function() {
            $("#vote_type").val("good");
            $("#vote_form").submit();
        });
    });

    function change_location(id) {
        url = "<?php echo base_url('public/translate/task'); ?>/";
        if($('#' + id)[0])
            window.location = url + id;
        else
            alert("There are no more tasks for this project");
    }

    function facebook_share() {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href),
                '', 'width=600,height=300');
    }

    function twitter_share() {
        window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('Please contribute by voting this translation <?php echo $hash; ?> ') +
                encodeURIComponent(window.location.href),
                '', 'width=600,height=300');
    }
</script>
<style>
    .invert{
        filter: invert(100%);
        -webkit-filter: invert(100%);
        -moz-filter: invert(100%);
        -o-filter: invert(100%);
        -ms-filter: invert(100%);
    }
</style>
<?php $this->load->view('_inc/footer'); ?>