<?php $this->load->view('_inc/header'); ?>

<!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide">
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

    <div class="container marketing">
        <div class="row">
            <div class="span12"><h4>This section will be populated soon.</h4></div>
            <!--<div class="span4">
                <img class="img-circle" data-src="holder.js/140x140">
                <h2>First project</h2>
                <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies
                    vehicula ut id elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo
                    cursus magna, vel scelerisque nisl consectetur et.</p>
                <p><a class="btn" href="./index_files/index.htm">View details »</a></p>
            </div>
            <div class="span4">
                <img class="img-circle" data-src="holder.js/140x140">
                <h2>Second project</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras
                    mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris
                    condimentum nibh, ut fermentum massa justo sit amet risus.</p>
                <p><a class="btn" href="./index_files/index.htm">View details »</a></p>
            </div>
            <div class="span4">
                <img class="img-circle" data-src="holder.js/140x140">
                <h2>Third project</h2>
                <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula
                    porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh,
                    ut fermentum massa justo sit amet risus.</p>
                <p><a class="btn" href="./index_files/index.htm">View details »</a></p>
            </div>-->
        </div>
 
<?php $this->load->view('_inc/footer'); ?>