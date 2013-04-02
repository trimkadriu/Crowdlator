<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <div class="alert alert-error">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4 class="alert-heading">Permission exception</h4>
        <p>You are not allowed to view this page. If you think this is a mistake please contact your administrator.</p>
        <a class="btn" onclick="history.back()">Go back !</a>
    </div>
</div>
<?php $this->load->view('_inc/footer_base'); ?>