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
                        <a href="#" rel="tooltip" data-placement="top" data-original-title="Ask for help on Facebook" onclick="facebook_share()">
                            <img src="<?php echo base_url("template/img/extra_icons/glyphicons_410_facebook.png"); ?>"
                                 style="width: 16px; height: 16px"
                                    rel="<?php echo base_url("admin/translate/task_id/".$task->id); ?>"/>
                        </a>
                        <a href="#" rel="tooltip" data-placement="top" data-original-title="Ask for help on Twitter" onclick="twitter_share()">
                            <img src="<?php echo base_url("template/img/extra_icons/glyphicons_411_twitter.png"); ?>"
                                 style="width: 16px; height: 16px"
                                    rel="<?php echo base_url("admin/translate/task_id/".$task->id); ?>"/>
                        </a>
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

    function facebook_share() {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(event.target.getAttribute("rel")),
                '', 'width=600,height=300');
    }

    function twitter_share() {
        window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('Please contribute to translate this task ') +
                encodeURIComponent(event.target.getAttribute("rel")),
                '', 'width=600,height=300');
    }
</script>
<?php $this->load->view('_inc/datatables'); ?>
<?php $this->load->view('_inc/footer_base'); ?>