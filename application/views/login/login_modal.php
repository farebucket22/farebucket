<!--modal screen-->
<div class="modal fade" id="login_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">Login</div>
            <div class="modal-body">
                <div class="login_logo"></div>
                <div id="error_alert" class="alert alert-danger" role="alert" ><span></span></div>
                <div id="success_alert" class="alert alert-success" role="alert" ><span></span></div>
                <!-- The form is placed inside the body of modal -->
                <form action="<?php echo base_url('index.php/login/guest_register'); ?>" id="guest_register" method="post" class="form-horizontal"  accept-charset="utf-8">
                    <div class="form-group">
                        <input type="email" class="form-control" name="guest_email" placeholder="E-Mail"/>
                    </div>
                    <button type="button" class="btn btn-change2" id="guest_reg_submit">Login</button>
                </form>
                <form action="<?php echo base_url('index.php/login/login_user_modal'); ?>" id="login_user" method="post" class="form-horizontal"  accept-charset="utf-8">
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="E-Mail"/>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password"/>
                    </div>
                    <button type="button" class="btn btn-change2" id="login_form_submit">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end modal screen-->