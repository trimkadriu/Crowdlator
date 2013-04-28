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
            <th style="width: 30%;">Date</th>
            <th style="width: 20%;">From</th>
            <th style="width: 20%;">To</th>
        </tr>
        </thead>
        <tbody>
        <?php if($translation_nr > 0){ for($i = 0; $i < $translation_nr; $i++) { ?>
            <tr>
                <td><?php echo $project_name[$i]; ?></td>
                <td><?php echo $translated_date[$i]; ?></td>
                <td><?php echo $translated_from[$i]; ?></td>
                <td><?php echo $translated_to[$i]; ?></td>
            </tr>
            <?php }}else{ ?>
                <tr><td colspan="5">There are no records</td></tr>
            <?php } ?>
        </tbody>
    </table><hr/>
    <a href="<?php echo base_url('admin/translate/my_translations'); ?>" class="btn btn-primary btn-small pull-right">View All ></a>
</div>