<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Projects -->
<div class="span8 round-border" style="margin: 0 20px 0 0; width: 570px;">
    <h4>
        My latest tasks
    </h4><hr/>
    <table width="100%" class="table table-hover">
        <thead>
        <tr>
            <th style="width: 30%;">Project Name</th>
            <th style="width: 30%;">Date</th>
            <th style="width: 20%;">From</th>
            <th style="width: 20%;">To</th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($tasks)) { $counter=0; foreach($tasks as $i=>$task) { ?>
            <?php if($project_status[$i] == "In Translation"){ if($counter == $limit) break; $counter++; ?>
            <tr>
                <td>
                    <?php echo $projectname[$i]; ?>
                </td>
                <td>
                    <?php echo $date[$i];//$parse = date_parse($date[$index]); echo $parse['day'].'-'.$parse['month'].'-'.$parse['year'] ;?>
                </td>
                <td>
                    <?php echo $from[$i]; ?>
                </td>
                <td>
                    <?php echo $to[$i]; ?>
                </td>
            </tr>
        <?php } } } else{ ?>
            <tr><td colspan="5">There are no records</td></tr>
        <?php } ?>
        </tbody>
    </table><hr/>
    <a href="<?php echo base_url('admin/projects/list_tasks'); ?>" class="btn btn-primary btn-small pull-right">View All ></a>
</div>