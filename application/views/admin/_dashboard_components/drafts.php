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
        <?php if($drafts) { for($i = 0; $i < sizeof($drafts); $i++) { ?>
            <tr>
                <td><?php echo $drafts[$i]->task_id; ?></td>
                <td style="text-align:center"><?php echo $drafts[$i]->date_created; ?></td>
            </tr>
                <?php }} else { ?>
        <tr><td colspan="3">There are no records</td></tr>
            <?php } ?>
        </tbody>
    </table><hr/>
    <a href="<?php echo base_url('admin/translate/draft_list'); ?>" class="btn btn-primary btn-small pull-right">View All ></a>
</div>