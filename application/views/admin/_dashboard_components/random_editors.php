<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- EDITORS -->
<div class="span4 round-border" style="margin: 0 20px 0 0">
    <h4>
        Editors
        <a href="<?php echo base_url('admin/users/list_users'); ?>" class="btn btn-link btn-small pull-right">
            Add new Editor
        </a>
    </h4><hr/>
    <table width="100%">
        <?php if($editors){
        foreach($editors as $editors_data) { ?>
            <tr>
            <tr>
                <td rowspan="4" style="width:90px; height:90px; padding-right:9px;">
                    <img src="<?php echo base_url().'template/img/default-profile.png'; ?>" class="img-polaroid" />
                </td>
                <td>
                    <h5>
                        <?php
                        echo $editors_data->fullname;
                        if($editors_data->role_id == 4)
                        {
                            echo ' <i class="icon-star" title="SUPER ADMIN"></i>';
                        }
                        ?>
                    </h5>
                </td>
            </tr>
            <tr>
                <td><?php echo $editors_data->country; ?></td>
            </tr>
            <tr>
                <td>
                    <?php echo $editors_data->email; ?>
                </td>
            </tr>
            <!--<tr>
                <td>
                    <a href="#">
                        <span class="pull-right badge badge-warning">Remove</span>
                    </a>
                    <a href="#">
                        <span class="pull-right badge badge-warning">Edit</span>
                    </a>
                </td>
            </tr>-->
            </tr>
            <tr><td style="height:9px;"></td></tr>
            <?php }
    }else{ ?>
        <tr><td>There are no records</td></tr>
        <?php } ?>
    </table><hr/>
    <a href="<?php echo base_url('admin/users/list_users'); ?>" class="btn btn-primary btn-small pull-right">View All ></a>
</div>