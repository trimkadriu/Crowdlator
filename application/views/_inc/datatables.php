<script>
    $(document).ready(function(){
        if ($('.datatable tbody tr td').text().substring(0, 2) != 'No'){
            $(".datatable").dataTable({
                "sPaginationType": "bootstrap",
                "aaSorting": [[ 0, "asc" ]]
            });
        }
    });
</script>
<script type="text/javascript" src="<?php echo base_url("template/js/jquery.dataTables.min.js"); ?>"></script>
<script type="text/javascript" src="<?php echo base_url("template/js/bootstrap.dataTables.js"); ?>"></script>
<!--<script type="text/javascript" src="<?php /*echo base_url("template/js/bootstrap.dataTables.sortNumbersHtml.js"); */?>"></script>-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url("template/css/bootstrap.dataTables.css"); ?>" />