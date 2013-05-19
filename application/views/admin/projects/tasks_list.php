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
                    <th style="width:10%; text-align: center;">Task ID</th>
                    <th style="width:50%;">Text</th>
                    <th style="width:10%;">Translations</th>
                    <th style="width:10%;">Contributed</th>
                    <th style="width:20%; text-align: center;">Action</th>
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
                    <td style="text-align: left;">
                        <a href="<?php echo base_url("admin/translate/task_id/".$task->id); ?>" rel="tooltip"
                           data-placement="top" data-original-title="Translate this text">
                            <i class="icon-text-width"></i> Translate task
                        </a><br/>
                        <a href="#" rel="tooltip" data-placement="top" data-original-title="Ask people to translate text for this task by sharing it on Facebook."
                           onclick="facebook_share('<?php echo urlencode(base_url("public/translate/task")."/".$task->id) ?>', $(this).attr('data-original-title'))">
                            <img src="<?php echo base_url("template/img/extra_icons/glyphicons_410_facebook.png"); ?>"
                                 style="width: 16px; height: 16px"
                                 rel="<?php echo base_url("public/translate/task/".$task->id); ?>"/> Facebook
                        </a><br/>
                        <a href="#" rel="tooltip" data-placement="top" data-original-title="Ask people to translate text for this task by sharing it on Twitter."
                           onclick="twitter_share('<?php echo urlencode(base_url("public/translate/task")."/".$task->id) ?>', 'Contribute by translating this task', '<?php echo urlencode($hash); ?>')">
                            <img src="<?php echo base_url("template/img/extra_icons/glyphicons_411_twitter.png"); ?>"
                                 style="width: 16px; height: 16px"
                                 rel="<?php echo base_url("public/translate/task/".$task->id); ?>"/> Twitter
                        </a><br/>
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

    function facebook_share(url, text) {
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + url, '', 'width=600,height=300');
    }

    function twitter_share(url, text, hash) {
        window.open('https://twitter.com/intent/tweet?text=' + text + ' ' + hash + ' ' + url, '', 'width=600,height=300');
    }
</script>
<?php $this->load->view('_inc/datatables'); ?>
<?php $this->load->view('_inc/footer_base'); ?>