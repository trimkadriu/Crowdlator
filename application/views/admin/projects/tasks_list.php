<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
    	Tasks list of "<?php echo $project_name; ?>"
	</h3><hr/>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th style="width:10%;text-align: center;">Task ID</th>
                    <th>Text</th>
                    <th>Translations</th>
                    <th>Contributed</th>
                    <th style="width:10%; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 0; foreach($tasks as $task) { ?>
                <tr id="<?php echo $task->id; ?>">
                    <td style="text-align: center;">
                        <?php echo $task->id; ?>
                    </td>
                    <td id="text<?php echo $index; ?>">
                        <?php echo $task->text; ?>
                    </td>
                    <td style="text-align: center"><?php echo $translation_count[$index]; ?></td>
                    <td style="text-align: center;">
                        <?php
                            if($contributed_in_translation[$index])
                                echo '<span style="display: none">1</span><i class="icon-ok"></i>';
                            else
                                echo '<span style="display: none">0</span><i class="icon-minus"></i>';
                        ?>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?php echo base_url("admin/translate/task_id/".$task->id); ?>" rel="tooltip" data-placement="top" data-original-title="Translate this text">
                        <i class="icon-text-width"></i></a>
                    </td>
                </tr>
                <?php $index++; } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();
    });
</script>
<?php $this->load->view('_inc/datatables'); ?>
<?php $this->load->view('_inc/footer_base'); ?>