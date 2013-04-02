<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
        <a class="brand active" href="<?php echo base_url().'admin/user/dashboard'; ?>">Dashboard</a>
        <div class="nav-collapse collapse">
          <ul class="nav">
			<li class="divider-vertical" style="height:50px;"></li>
            <li class="dropdown">
                <a id="projects-drop" data-target="#" role="button" class="dropdown-toggle" data-toggle="dropdown" style="cursor:pointer;">
                    Projects <b class="caret"></b>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="projects-drop">
                	<?php if(check_permissions(get_session_roleid(), 'admin/projects/create_project')) { ?>
                    	<li>
                            <a tabindex="-1" href="<?php echo base_url('admin/projects/create_project'); ?>">
                                Create new
                            </a>
                        </li>
                    <?php } ?>
                    <!-- <li><a tabindex="-1" href="#">Edit</a></li> -->
                    <?php if(check_permissions(get_session_roleid(), 'admin/projects/list_projects')) { ?>
                        <li>
                            <a tabindex="-1" href="<?php echo base_url('admin/projects/list_projects'); ?>">
                                List
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(check_permissions(get_session_roleid(), 'admin/projects/list_tasks')) { ?>
                        <li>
                            <a tabindex="-1" href="<?php echo base_url('admin/projects/list_tasks'); ?>">
                                List all tasks
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <li class="dropdown">
                <a id="translations-drop" data-target="#" role="button" class="dropdown-toggle" data-toggle="dropdown" style="cursor:pointer;">
					Translations <b class="caret"></b>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="translations-drop">
                    <?php if(check_permissions(get_session_roleid(), 'admin/translate/translations')) { ?>
                        <li>
                            <a tabindex="-1" href="<?php echo base_url('admin/translate/translations'); ?>">
                                List of translations
                            </a>
                        </li>
                    <?php } ?>
                    <?php if(check_permissions(get_session_roleid(), 'admin/translate/drafts')) { ?>
                        <li><a tabindex="-1" href="#">Drafts</a></li>
                    <?php } ?>
                    <?php if(check_permissions(get_session_roleid(), 'admin/translate/my_translations')) { ?>
                        <li>
                            <a tabindex="-1" href="<?php echo base_url("admin/translate/my_translations"); ?>">
                                My translations
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </li>
            <?php if(check_permissions(get_session_roleid(), 'admin/users/list_users')) { ?>
                <li>
                    <a href="<?php echo base_url("admin/users/list_users") ?>">
                        Users
                    </a>
                </li>
            <?php } ?>
            <li class="dropdown">
                <a id="settings-drop" data-target="#" role="button" class="dropdown-toggle" data-toggle="dropdown" style="cursor:pointer;">
					Settings <b class="caret"></b>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="settings-drop">
                    <li>
                        <a tabindex="-1" href="<?php echo base_url('admin/settings/account_settings'); ?>">
                            Account
                        </a>
                    </li>
                    <!--<li class="divider"></li>-->
                    <!--<li><a tabindex="-1" href="#">System Settings</a></li>-->
                </ul>
            </li>
            </ul>
            <ul class="nav pull-right">
				<li class="divider-vertical" style="height:50px;"></li>
                <li>
                    <a href="<?php echo base_url('admin/user/logout'); ?>">
                        <i class="icon-off icon-white"></i> Log out
                    </a>
                </li>
			</ul>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
</div>
<div class="container">        
    <div class="span12" style="margin-left:0px; margin-top:70px;">
		<?php
			//NOTIFICATION MESSAGE
            if(get_session_message() != false)
            {
				$message_data = get_session_message();
                echo '<div class="alert alert-'.$message_data['message_type'].'">'.
                        '<button type="button" class="close" data-dismiss="alert">×</button>'.
                        '<strong>'.strtoupper($message_data['message_type']).': </strong>'.$message_data['message'].
                     '</div>';
            }
        ?>
    </div>
</div>