<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Projects -->
<div class="span4 round-border" style="margin: 0px;">
    <h4>
        Drafts
    </h4><hr/>
    <table width="100%" class="table table-hover">
        <thead>
        <tr>
            <th>Task ID</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        <?php $projects = false; if($projects){
            foreach($projects as $project) { ?>
            <tr>
                <td><?php echo $project->project_name; ?></td>
                <td style="text-align:center"><?php echo $project->status; ?></td>
            </tr>
                <?php }}else{ ?>
        <tr><td colspan="3">There are no records</td></tr>
            <?php } ?>
        </tbody>
    </table><hr/>
    <a href="<?php echo base_url('admin/projects/list_projects'); ?>" class="btn btn-primary btn-small pull-right">View All ></a>
</div>