<!DOCTYPE html>
<html en="english">
    <head>
        <meta name="viewport" content="width=device-width" initial-scale="1"> 
        <title>Admin-Loginpage</title>
        <!-- stylesheet-->
         <link href="<?php echo base_url('css/bootstrap.min.css')?>" rel="stylesheet">
         <link href="<?php echo base_url('css/custom.css')?>" rel="stylesheet">
         <link href='http://fonts.googleapis.com/css?family=Exo' rel='stylesheet' type='text/css'>
         <link href='http://fonts.googleapis.com/css?family=Raleway:400,500' rel='stylesheet' type='text/css'>
        <!--scritps-->
         <script src="http://code.jquery.com/jquery-latest.js"></script>
         <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
         <script src="<?php echo base_url('js/vendor/bootstrap.min.js');?>"></script>
         <script src="<?php echo base_url('js/vendor/jqBootstrapValidation.js') ;?>"></script>
         <script src="<?php echo base_url('js/vendor/bootstrap-maxlength.min.js') ;?>"></script>
         
    </head>
    <script>
        $(function(){
            $("input,select,textarea").jqBootstrapValidation();
            $('input[maxlength]').maxlength({
            alwaysShow: true
            });
            $('textarea').maxlength({
            alwaysShow: true
            });
        });
     </script>
    <body class="main"> 
          
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
       <div class="container">
           <div class="row">
             <div class="login_form login_screen" >
                   
                    <h2 class="heading">Admin Login</h2>
                    
                    <div class="panel-body">
                        <form name="loginform" method="post" 
                              action="<?php echo site_url('admin/login/checklogin');?>" 
                              class="form-horizontal" role="form" enctype="multipart/form-data" novalidate>
                            <div class="control-group">
                              <label class="control-label col-lg-3" for="email">Email</label>
                              <div class="col-lg-9 controls">
                                <input type="email" class="form-control" id="email" 
                                       name="email" placeholder="Email id" required data-validation-required-message="Email is required" />
                                <p class="help-block"></p>
                              </div>
                            </div>
                            <div class="control-group">
                             <label class="control-label col-lg-3" for="password">Password</label>               
                             <div class="col-lg-9 controls">
                               <input type="password" class="form-control" id="password" name="pwd" placeholder="Password" required data-validation-required-message="Password is required">
                               <p class="help-block"></p>
                             </div>
                            </div>
                            <!--div class="form-group">
                                <div class="col-lg-9 col-lg-offset-3">
                                    <div class="checkbox" style="margin-left: 3%;">
                                      <label>
                                        <input type="checkbox"> Remember me
                                      </label>
                                    </div>
                                </div>
                            </div-->
                            <div class="form-group">
                            <div class="col-lg-9  col-lg-offset-3">
                               <button type="submit" class="btn btn-info btn-sm" style="margin-left:3%;">Sign in</button>
                            </div>
                            
                            </div>
                        </form>
                    </div>
                </div>
             </div>
        </div> 
    </body>
</html>

