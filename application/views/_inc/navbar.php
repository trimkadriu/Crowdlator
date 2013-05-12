<div class="navbar-wrapper">
  <div class="container">
    <div class="navbar navbar-inverse">
      <div class="navbar-inner">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> 
        	<span class="icon-bar"></span> 
            <span class="icon-bar"></span> 
            <span class="icon-bar"></span> 
		</a> 
        <a class="brand" href="<?php echo base_url().'pages/home'; ?>"><?php echo $this->config->item("application_logo"); ?></a>
        <div class="nav-collapse collapse">
          <ul class="nav">
            <li class="divider-vertical" style="height:50px;"> </li>
            <li><a href="<?php echo base_url().'pages/register'; ?>">Register</a></li>
            <li><a href="<?php echo base_url().'pages/projects'; ?>">Projects</a></li>
            <li class="dropdown" style="cursor:pointer"> <a data-target="#"  class="dropdown-toggle" data-toggle="dropdown">Help <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url().'pages/faq'; ?>">Frequently asked questions</a></li>
                <li><a href="<?php echo base_url().'pages/contact'; ?>">Contact</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav pull-right">
            <li class="divider-vertical" style="height:50px;"> </li>
            <li>
            <?php
            	if(get_session_loggedin() != 'true')
				{
					$attributes = array('class' => 'navbar-form form-inline', 'style' => 'margin-top:5px');
            		echo form_open(base_url().'admin/user/login', $attributes);
                    $input_username = array('type' => 'text', 'name' => 'username', 'placeholder' => 'Username', 
										'required' => 'required', 'class' => 'input-small', 'style' => 'margin-right:5px;');
					echo form_input($input_username);
                    $input_password = array('type' => 'password', 'name' => 'password', 'placeholder' => 'Password', 
										'required' => 'required', 'class' => 'input-small', 'style' => 'margin-right:5px;');
					echo form_input($input_password);
                    echo '<button class="btn btn-primary" type="submit">Login</button>';
                    echo form_close();
				}
				else
				{
					echo '<a href="'.base_url().'admin/user"><strong>Administration Panel</strong></a>';
				}
			?>
            </li>
          </ul>
        </div>
        <!--/.nav-collapse --> 
      </div>
      <!-- /.navbar-inner --> 
    </div>
    <!-- /.navbar --> 
    <?php
	if(get_session_loggedin() == 'true')
	{
	   echo '<span class="alert alert-info pull-right">'.	   
			'<span>Welcome <strong>'.get_session_fullname().
			'</strong> ('.get_session_username().') - </span>'.
			'<strong><a href="'.base_url().'admin/user/logout">'.
			'Logout</a></strong>'.
			'<button type="button" class="close" data-dismiss="alert">×</button></span>';
	}
	if(get_session_message() != false)
	{ ?>
    <div class="span12" style="margin-left:0px;">
    <?php
		$message_data = get_session_message();
		echo '<div class="alert alert-'.$message_data['message_type'].'">'.
				'<button type="button" class="close" data-dismiss="alert">×</button>'.
				'<strong>'.strtoupper($message_data['message_type']).': </strong>'.$message_data['message'].
			 '</div>'; ?>
    </div>
    <?php } ?>
  </div>
  <!-- /.container --> 
</div>
<!-- /.navbar-wrapper -->