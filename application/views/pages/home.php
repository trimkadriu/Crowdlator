<?php $this->load->view('_inc/header'); ?>

<!-- Carousel
    ================================================== -->
    <div id="myCarousel" class="carousel slide">
      <div class="carousel-inner">
        <div class="item active">
          <img src="<?php echo base_url().'template/images/slide-01.jpg'; ?>" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Easy translations</h1>
              <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <a class="btn btn-large btn-primary" href="./index_files/index.htm">Sign up today</a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="<?php echo base_url().'template/images/slide-02.jpg'; ?>" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>Youtube video annotations</h1>
              <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <a class="btn btn-large btn-primary" href="./index_files/index.htm">Learn more</a>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="<?php echo base_url().'template/images/slide-03.jpg'; ?>" alt="">
          <div class="container">
            <div class="carousel-caption">
              <h1>SoundCloud audio annotations</h1>
              <p class="lead">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <a class="btn btn-large btn-primary" href="./index_files/index.htm">Browse gallery</a>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="http://twitter.github.com/bootstrap/examples/carousel.html#myCarousel" data-slide="prev">‹</a>
      <a class="right carousel-control" href="http://twitter.github.com/bootstrap/examples/carousel.html#myCarousel" data-slide="next">›</a>
    </div><!-- /.carousel -->

    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class="span4">
          <img class="img-circle" data-src="holder.js/140x140">
          <h2>First project</h2>
          <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.</p>
          <p><a class="btn" href="./index_files/index.htm">View details »</a></p>
        </div><!-- /.span4 -->
        <div class="span4">
          <img class="img-circle" data-src="holder.js/140x140">
          <h2>Second project</h2>
          <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn" href="./index_files/index.htm">View details »</a></p>
        </div><!-- /.span4 -->
        <div class="span4">
          <img class="img-circle" data-src="holder.js/140x140">
          <h2>Third project</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn" href="./index_files/index.htm">View details »</a></p>
        </div><!-- /.span4 -->
      </div><!-- /.row -->

 
<?php $this->load->view('_inc/footer'); ?>