<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
    	All assigned tasks
	</h3><hr/>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
                <tr>
                    <th style="width:7%;text-align: center;">ID</th>
                    <th style="width:20%;">Project name</th>
                    <th style="width:48%;">Text</th>
                    <th style="width:15%; text-align: center;">Translations</th>
                    <th style="width:10%; text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 0; if($tasks) { foreach($tasks as $task) { ?>
                <tr id="<?php echo $task->id; ?>">
                    <td style="text-align: center;">
                        <?php echo $task->id; ?>
                    </td>
                    <td>
                        <?php echo $projectname[$index]; ?>
                    </td>
                    <td>
                        <?php echo $task->text; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php echo $count[$index]; ?>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?php echo base_url("admin/translate/task_id/".$task->id); ?>"rel="tooltip" data-placement="top" data-original-title="Translate this text">
                        <i class="icon-text-width"></i></a>
                    </td>
                </tr>
                <?php $index++; } } ?>
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