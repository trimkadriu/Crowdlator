<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Projects -->
<div class="span4 round-border" style="margin: 0 20px 0 0">
    <h4>
        Projects
        <a href="<?php echo base_url('admin/projects/create_project'); ?>" class="btn btn-link btn-small pull-right">
            Add new Project
        </a>
    </h4><hr/>
    <table width="100%" class="table table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Project Name</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php if($projects){ foreach($projects as $project) { ?>
            <tr>
                <td><?php echo $project->id; ?></td>
                <td><?php echo $project->project_name; ?></td>
                <td><?php echo $project->status; ?></td>
            </tr>
                <?php }} else{ ?>
        <tr><td colspan="3">There are no records</td></tr>
            <?php } ?>
        </tbody>
    </table><hr/>
    <a href="<?php echo base_url('admin/projects/list_projects'); ?>" class="btn btn-primary btn-small pull-right">View All ></a>
</div>