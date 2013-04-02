<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Projects -->
<div class="span8 round-border" style="margin: 0 20px 0 0; width: 570px;">
    <h4>
        My latest translations
    </h4><hr/>
    <table width="100%" class="table table-hover">
        <thead>
        <tr>
            <th style="width: 30%;">Project Name</th>
            <th style="width: 25%;">Date</th>
            <th style="width: 20%;">From</th>
            <th style="width: 20%;">To</th>
            <th style="width: 5%;">Type</th>
        </tr>
        </thead>
        <tbody>
        <?php $projects = false;  if($projects){
            foreach($projects as $project) { ?>
            <tr>
                <td><?php echo $project->project_name; ?></td>
                <td style="text-align:center"><?php echo $project->status; ?></td>
            </tr>
                <?php }}else{ ?>
            <tr><td colspan="5">There are no records</td></tr>
            <?php } ?>
        </tbody>
    </table><hr/>
    <a href="<?php echo base_url('admin/projects/list_projects'); ?>" class="btn btn-primary btn-small pull-right">View All ></a>
</div>