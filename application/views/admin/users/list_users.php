<?php $this->load->view('_inc/header_admin'); ?>
<body>
<div class="container round-border">
    <h3 style="margin-left:20px">
        List of users
    </h3><hr/>
    <div style="margin:20px;">
        <table class="table table-hover datatable">
            <thead>
            <tr>
                <!--<th style="width:10%;">User ID</th>-->
                <th>Full name<br/>Date created</th>
                <th>Country</th>
                <th>E-mail</th>
                <th>Role</th>
                <th style="width: 15%; text-align: center">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if($users != null) { $index = 0; foreach($users as $user) { ?>
            <tr>
                <!--<td><?php /*echo $user->id; */?></td>-->
                <td id="user_fullname">
                    <span style="display: none;"><?php echo $user->date_created; ?></span>
                    <?php echo $user->fullname; ?><br/><?php echo $user->date_created; ?>
                </td>
                <td><?php echo $user->country; ?></td>
                <td id="user_email"><?php echo $user->email; ?></td>
                <td id="user_rolename"><?php echo ucfirst($user->rolename); ?></td>
                <td style="text-align: center;">
                    <a href="#change_role_modal"
                       onclick="set_values($(this).parent().parent().find('#user_fullname').text(), $(this).parent().parent().find('#user_rolename').text(), $(this).parent().parent().find('#user_email').text())"
                       rel="tooltip" data-placement="top" data-original-title="Change user role" data-toggle="modal">
                        <i class="icon-edit"></i> Change role
                    </a>
                </td>
            </tr>
                <?php $index++; } } else {?>
            <tr><td colspan="6">No users found.</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<input type="hidden" id="row_id" value=""/>
<div class="modal hide fade" id="change_role_modal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Change user role</h3>
    </div>
    <div class="modal-body">
        <p>Select role for <strong><span id="fullname"></span></strong></p>
        <p>
            <label>Roles: </label>
            <select id="roles">
                <?php foreach($roles as $role) { ?>
                <option value="<?php echo $role->id ?>"><?php echo ucfirst($role->role); ?></option>
                <?php } ?>
            </select>
        </p>
    </div>
    <form id="change_role_form" action="<?php echo base_url("admin/users/change_user_role"); ?>" method="post">
        <input type="hidden" name="email" value=""/>
        <input type="hidden" name="role" value="">
    </form>
    <div class="modal-footer">
        <a class="btn" data-dismiss="modal">Cancel</a>
        <a id="change_role" class="btn btn-primary">Change role</a>
    </div>
</div>
<script>
    function set_values(fullname, role, email) {
        $('#fullname').text(fullname);
        $('#roles option').filter(function() {
            return $(this).text() == role;
        }).attr('selected', true);
        $("#change_role_form input[name='email']").val(email);
        $("#change_role_form input[name='role']").val($('#roles').val());
    }

    $('#change_role').click(function(){
        $('#change_role_form').submit();
    });

    $(document).ready(function() {
        $("[rel=tooltip]").tooltip();
        $("#roles").change(function(){
            $("#change_role_form input[name='role']").val($(this).val());
        });
    });
</script>
<?php $this->load->view('_inc/datatables'); ?>
<script>
    $(document).ready(function() {
        $(".datatable thead tr th:nth-child(1)").click();
    });
</script>
<?php $this->load->view('_inc/footer_base'); ?>