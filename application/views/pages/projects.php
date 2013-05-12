<?php $this->load->view('_inc/header'); ?>
<?php $this->load->view('_inc/navbar'); ?>
<div class="container" style="margin-top:80px;">
    <div>
        <h3>Projects that are being translated in Crowdlator</h3><?php if(isset($projects)) { ?>
        <?php foreach($projects as $key=>$project) {?>
            <div class="span4" style="width: 290px; margin: 0 20px 20px 0px;">
                <div style="position: relative">
                    <a onclick="prepare_video_modal('<?php echo $project->video_id; ?>')" data-toggle="modal" href="#video_modal">
                        <img class="img-polaroid" src="http://img.youtube.com/vi/<?php echo $project->video_id; ?>/0.jpg" />
                        <img src="<?php echo base_url("template/img/play_button.png"); ?>"
                             style="position: absolute; top: 85px; left: 123px; height: 64px; width: 64px;"/>
                    </a>
                </div>
                <h3><?php echo $project->project_name; ?></h3>
                <p style="height: 65px;"><?php echo substr($project->project_description, 0 , 115); ?></p>
                <div class="clearfix pull-right">
                    <a class="btn" href="<?php echo base_url('public/translate/project/'.$project->id); ?>">Translate</a>
                </div>
            </div>
            <?php if(($key + 1) % 3 == 0) { ?><div class="clearfix"></div><?php } ?>
            <?php } ?>
        <?php } else { ?>
        <div class="span12"><h4>This section will be populated soon.</h4></div>
        <?php } ?>
    </div>
</div>
<div class="modal hide fade" id="video_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        Video
    </div>
    <div class="modal-body">
        <!-- Youtube Video Here -->
        <iframe id="video" width="500" height="280" frameborder="0" allowfullscreen></iframe>
        <!-- End of Video Here -->
    </div>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Close</a>
    </div>
</div>
<?php $this->load->view('_inc/footer'); ?>
<script>
    function prepare_video_modal(video_id) {
        $("#video").attr("src", "http://www.youtube.com/embed/" + video_id);
    }
</script>