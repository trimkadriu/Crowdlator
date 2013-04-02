<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container">
	<!-- DASHBOARD -->
    <div class="round-border well" style="padding:20px; display:inline-block; width:95%; position: relative">
        <h4 style="float: right;">
            <a href="<?php echo base_url('pages/home'); ?>" style="color: #333">
                <i class="icon-home" style="margin: 3px 5px;"></i>Homepage
            </a>
        </h4>
        <!-- / FIRST AND LAST NAME - USERNAME info shown on top -->
        <!--<div style="position: absolute; right: 20px; top: 70px; width: 45%;">
            <span class="alert alert-info pull-right">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <span><strong><?php /*echo get_session_fullname(); */?></strong>
                    <?php /*echo ' ('.get_session_username().')'; */?></span>
            </span>
        </div>-->
        <h2>
            Welcome to Crowdlator!<br/>
            <small>We’ve assembled some links to get you started:</small>
        </h2><br/>
        <div style="width:33%; display:inline; float:left">
            <p><strong><?php echo $title1; ?></strong><br/><br/>
                <a class="btn btn-primary btn-large" href="<?php echo $button[1] ?>"
                	style="font-size:16px">
                    <?php echo $button[0]; ?>
                </a>
            </p>
        </div>
        <div style="width:33%; display:inline; float:left">
            <p><strong><?php echo $title2; ?></strong><br/>
                <?php echo $left1[1]; ?>
                <a class="btn btn-link" href="<?php echo $left1[2]; ?>"
                	style="font-size:16px; margin-top:8px; padding-left:0px">
                    <?php echo $left1[0]; ?>
                </a><br/>
                <?php echo $left2[1]; ?>
                <a class="btn btn-link" href="<?php echo $left2[2]; ?>"
                	style="font-size:16px; padding-left:0px">
                    <?php echo $left2[0]; ?>
                </a>
            </p>
        </div>
        <div style="width:33%; display:inline; float:right">
            <p><strong><?php echo $title3; ?></strong><br/>
                <?php echo $right1[1]; ?>
                <a class="btn btn-link" href="<?php echo $right1[2]; ?>"
                	style="font-size:16px; margin-top:8px; padding-left:0px">
                    <?php echo $right1[0]; ?>
                </a><br/>
                <?php echo $right2[1]; ?>
                <a class="btn btn-link" href="<?php echo $right2[2]; ?>"
                	style="font-size:16px; padding-left:0px">
                    <?php echo $right2[0]; ?>
                </a>
            </p>
        </div>
    </div>
    <!-- Administrator -->
    <?php
    if(get_user_role() == 'administrator') {
        // Random editors
        $this->load->view('admin/_dashboard_components/random_editors');
        // Latest projects
        $this->load->view('admin/_dashboard_components/latest_projects');
    }
    ?>
    <!-- Editor -->
    <?php
    if(get_user_role() == 'editor') {
        // My latest tasks
        $this->load->view('admin/_dashboard_components/my_latest_tasks');
        // Notifications
        $this->load->view('admin/_dashboard_components/latest_translations');
    }
    ?>
    <!-- Translator -->
    <?php
        if(get_user_role() == 'translator'){
            // My latest translations
            $this->load->view('admin/_dashboard_components/my_latest_translations');
            // Drafts
            $this->load->view('admin/_dashboard_components/drafts');
        }
    ?>
</div>
<?php $this->load->view('_inc/footer'); ?>