<?php $this->load->view('_inc/header'); ?>

<!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide" style="margin-bottom: 40px">
      <div class="carousel-inner">
        <div class="item active">
          <img src="<?php echo base_url().'template/images/slide-01.jpg'; ?>" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Crowdsourcing Translations</h1>
              <p class="lead">Smartest way to translate your videos. Crowdlator is social translation tool that helps you to manage translations of videos.</p>
              <a class="btn btn-large btn-primary" href="<?php echo base_url("pages/register"); ?>">Sign up today</a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="<?php echo base_url().'template/images/slide-02.jpg'; ?>" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Direct Share</h1>
              <p class="lead">Your video is linked directly with Youtube and your translated audio with SoundCloud. Also you can share your tasks to be translated to Facebook and Twitter.</p>
              <a class="btn btn-large btn-primary" href="<?php echo base_url("pages/faq"); ?>">Learn more</a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="<?php echo base_url().'template/images/slide-03.jpg'; ?>" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Re-Rendering video with translated voice</h1>
              <p class="lead">You can re-render your video with the translated voice recorder by crowd, then you can share, download, and publish it.</p>
              <a class="btn btn-large btn-primary" href="<?php echo base_url("pages/faq"); ?>">Learn more</a>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="<?php echo base_url("pages/home"); ?>#myCarousel" data-slide="prev">‹</a>
      <a class="right carousel-control" href="<?php echo base_url("pages/home"); ?>#myCarousel" data-slide="next">›</a>
    </div><!-- /.carousel -->

    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing" style="padding-left: 20px;">
        <div class="row">
            <?php if(isset($projects)) { ?>
            <h2 style="padding-left: 20px; margin-bottom: 30px">Some of the projects that are being translated.</h2>
            <?php foreach($projects as $project) {?>
                <div class="span4">
                    <div style="position: relative">
                        <a onclick="prepare_video_modal('<?php echo $project->video_id; ?>')" data-toggle="modal" href="#video_modal">
                            <img class="img-polaroid" src="http://img.youtube.com/vi/<?php echo $project->video_id; ?>/0.jpg" />
                            <img src="<?php echo base_url("template/img/play_button.png"); ?>"
                                style="position: absolute; top: 85px; left: 123px; height: 64px; width: 64px;"/>
                        </a>
                    </div>
                    <h3><?php echo $project->project_name; ?></h3>
                    <p><?php echo $project->project_description; ?></p>
                </div>
            <?php } ?>
            <div class="span12">
                <a class="btn btn-primary pull-right" href="<?php echo base_url('pages/translations')?>">See more projects »</a>
            </div>
            <?php } else { ?>
                <div class="span12"><h4>This section will be populated soon.</h4></div>
            <?php } ?>
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