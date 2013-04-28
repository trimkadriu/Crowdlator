<?php $this->load->view('_inc/header'); ?>
<?php $this->load->view('_inc/navbar'); ?>
<div class="container" style="margin-top:80px;" xmlns="http://www.w3.org/1999/html">
    <div>
        <h3>Contribute by voting this translation</h3>
        <div class="span6" style="margin-left:0px;">
            <strong>Tranlation from <?php echo $translate_from; ?>:</strong><br/>
            <textarea disabled="disabled" class="translate_text" style="width: 95%; height: 150px; margin-top: 5px;"><?php echo $text; ?></textarea><br/><br/>
            <strong>Translated to <?php echo $translate_to; ?>:</strong><br/>
            <textarea disabled="disabled" name="translated" style="width: 95%; height: 150px; margin-top: 5px; resize: none;"><?php echo $translated; ?></textarea>
            <input type="hidden" name="translation_id" value="<?php echo $translation_id; ?>"/>
            <div style="text-align: center">
                <button type="button" class="btn btn-danger" style="width: 45%; margin: 0 5px 0 -5px">
                    <img style="height: 16px;" class="invert"
                         src="<?php echo base_url("template/img/extra_icons/glyphicons_344_thumbs_down.png"); ?>"/> Bad
                </button>
                <button type="button" class="btn btn-success" style="width: 45%">
                    <img style="height: 16px;" class="invert"
                         src="<?php echo base_url("template/img/extra_icons/glyphicons_343_thumbs_up.png"); ?>"/> Good
                </button>
                <!--<input style="margin-bottom: 10px;" type="button" class="btn btn-primary" value="Next >"
                       onclick="change_location($('#<?php /*echo $translation_id; */?>').next().attr('id'))"/>
                <input name="public" type="hidden" value="public">-->
            </div>
            <!-- reCaptcha CODE -->
            <!--<<script type="text/javascript">
                var RecaptchaOptions = {theme : 'clean'};
            </script>
            div style="margin-bottom: 15px;">
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
                <!--<h3>Try other tasks of the same project:</h3>-->
                <ul id="list" style="display: none;">
                    <?php foreach($next_tasks as $next_task) { ?>
                    <li id="<?php echo $next_task->id ?>">
                        <a href="<?php echo base_url('public/translate/task/'.$next_task->id); ?>">
                            Task with ID-<?php echo $next_task->id ?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
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
        window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('Please contribute by voting this translation') +
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