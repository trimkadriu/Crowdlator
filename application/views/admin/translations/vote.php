<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
    	Contribute by voting a translation for "<?php echo $project_name; ?>" project
	</h3><hr/>
    <div style="margin:20px;" class="span6">
        <strong>Translation from <?php echo $translate_from; ?>:</strong><br/>
        <textarea disabled="disabled" class="translate_text"><?php echo $text; ?></textarea><br/><br/>
        <strong>Translated to <?php echo $translate_to; ?>:</strong><br/>
        <textarea disabled="disabled" class="translate_text"><?php echo $translated; ?></textarea>
        <input type="hidden" name="translation_id" value="<?php echo $translation_id; ?>"/>
        <div class="pull-right" style="margin-top: 10px;">
            <button type="button" class="btn btn-danger" style="width: 100px; margin-right: 5px;">
                <img style="height: 16px;" class="invert"
                     src="<?php echo base_url("template/img/extra_icons/glyphicons_344_thumbs_down.png"); ?>"/> Bad
            </button>
            <button type="button" class="btn btn-success" style="width: 100px;">
                <img style="height: 16px;" class="invert"
                     src="<?php echo base_url("template/img/extra_icons/glyphicons_343_thumbs_up.png"); ?>"/> Good
            </button>
        </div>
    </div>
    <div class="span5" style="border-left: 1px solid #eeeeee; padding: 10px;">
        <h4>More details</h4><hr/>
        <iframe width="380" height="213" src="http://www.youtube.com/embed/<?php echo $project_video_id; ?>" frameborder="0" allowfullscreen></iframe>
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
<script>
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
    .translate_text {
        width: 100%;
        height: 150px;
        margin-top: 5px;
        resize: none;
    }

    .invert{
        filter: invert(100%);
        -webkit-filter: invert(100%);
        -moz-filter: invert(100%);
        -o-filter: invert(100%);
        -ms-filter: invert(100%);
    }
</style>
<?php $this->load->view('_inc/footer_base'); ?>