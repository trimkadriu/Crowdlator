<?php $this->load->view('_inc/header'); ?>
<?php $this->load->view('_inc/navbar'); ?>
<div class="container" style="margin-top:80px;">
    <div>
        <h3>Contribute in translating this task</h3>
        <div class="span6" style="margin-left:0px;">
            <form action="<?php echo base_url("admin/translate/task_id"); ?>" method="post">
                <strong>Translate from <?php echo $translate_from; ?>:</strong><br/>
                <textarea disabled="disabled" class="translate_text" style="width: 95%; height: 150px; margin-top: 5px;"><?php echo $text; ?></textarea><br/><br/>
                <strong>Translate to <?php echo $translate_to; ?>:</strong><br/>
                <textarea name="translated" style="width: 95%; height: 150px; margin-top: 5px; resize: none;"></textarea>
                <input type="hidden" name="task_id" value="<?php echo $task_id; ?>"/>
                <!-- reCaptcha CODE -->
                <script type="text/javascript">
                    var RecaptchaOptions = {theme : 'clean'};
                </script>
                <div style="margin-bottom: 15px;">
                    <script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=<?php echo $recaptcha_public_key; ?>">
                    </script>
                    <noscript>
                        <iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php echo $recaptcha_public_key; ?>"
                                height="200" width="500" frameborder="0"></iframe><br>
                        <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                        <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
                    </noscript>
                </div>
                <!-- reCaptcha CODE -->
                <input type="button" class="btn btn-danger" value="Skip"
                       onclick="change_location($('#<?php echo $task_id; ?>').next().attr('id'))"/>
                <div class="pull-right">
                    <span rel="tooltip" data-placement="top" data-original-title="You need to register in Crowdlator in order to save drafts.">
                        <input type="button" class="btn btn-info" value="Save to draft" style="cursor: not-allowed;"/>
                    </span>
                    <input type="submit" class="btn btn-primary" value="Translate"/>
                </div>
                <input name="public" type="hidden" value="public">
            </form>
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
                <span rel="tooltip" data-placement="top" data-original-title="Ask for translation by sharing it on Facebook.">
                    <a href="#" onclick="facebook_share()">
                        <img src="<?php echo base_url("template/img/extra_icons/glyphicons_410_facebook.png"); ?>"
                             style="width: 28px; height: 28px"/>
                    </a>
                </span>
                <span rel="tooltip" data-placement="top" data-original-title="Ask for translation by sharing it on Twitter.">
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
        window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('Please contribute to translate this task ') +
                encodeURIComponent(window.location.href),
                '', 'width=600,height=300');
    }
</script>
<?php $this->load->view('_inc/footer'); ?>