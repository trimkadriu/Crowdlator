<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- Projects -->
<div class="span4 round-border" style="margin: 0px;">
    <h4>
        Translations
    </h4><hr/>
    <table width="100%" class="table table-hover">
        <thead>
        <tr>
            <th style="text-align:center">ID</th>
            <th>Date</th>
            <th>Reviewed</th>
        </tr>
        </thead>
        <tbody>
        <?php if($translations) { foreach($translations as $translation) { ?>
            <tr>
                <td style="text-align:center"><?php echo $translation->task_id; ?></td>
                <td><?php echo $translation->date_created; ?></td>
                <td style="text-align:center">
                    <?php
                    if($translation->reviewed){
                        echo '<span style="display: none">1</span><i class="icon-ok"></i>';
                    }
                    else{
                        echo '<span style="display: none">0</span><i class="icon-minus"></i>';
                    }
                    ?>
                </td>
            </tr>
                <?php }} else{ ?>
            <tr><td colspan="3">There are no records</td></tr>
            <?php } ?>
        </tbody>
    </table><hr/>
    <a href="<?php echo base_url('admin/translate/translations'); ?>" class="btn btn-primary btn-small pull-right">View All ></a>
</div>