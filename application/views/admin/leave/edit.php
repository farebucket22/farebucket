<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Farebucket - Admin</title>
    <link href="<?php echo base_url('css/style.css');?>" rel="stylesheet" type="text/css">
    <link href=<?php echo base_url('css/bootstrap.css');?> rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>css/custom.css" type="text/css" rel="stylesheet" media="screen">       
    <script src="https://code.jquery.com/jquery-latest.js"></script>
    <script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
    <script src="<?php echo base_url('bootstrap/js/bootstrap.min.js');?>"></script>
    
</head>
<style>
    .user-dropdown{
          width:90px;
      }
</style>
<body>
    <div class="container content">
      <h2 style = "margin-top:60px;">Edit Leave</h2>
      
        <?php if($this->session->flashdata('returnMsg')) : ?>
            <?php echo $this->session->flashdata('returnMsg')?>
        <?php endif; ?>
          
        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger">
                <h6><?php echo validation_errors(); ?></h6>
            </div>
        <?php endif; ?>
      
      <div class="edit-form span6">
    
    <?php
        $activity_leave_days = explode(',', $leave->activity_leave_days);
    ?>

    <form action="<?php echo site_url('admin/leave/update?activity_leave_id='.$leave->activity_leave_id); ?>" method="post" enctype="multipart/form-data">
        <label class="control-label">Leave Name</label>
        <input type="text" class="input-block-level" placeholder="Leave Name" name="activity_leave_name" value="<?php echo $leave->activity_leave_name; ?>">
        <label class="control-label">Select Activity</label>
        <select name="activity" style="width:180px;" class="span6">
            <option value="">Select</option>
            <?php foreach($activities as $activity) { ?>
                <option value="<?php echo $activity->activity_id; ?>" <?php if($activity->activity_id == $leave->activity_id) echo 'selected'; ?>><?php echo $activity->activity_name; ?></option>
            <?php } ?>
        </select>
        <label class="control-label">Leave Dates</label>
        <input type="text" class="input-block-level" placeholder="Leave Dates" name="activity_leave_dates" value="<?php echo $leave->activity_leave_dates; ?>">
        <div class="row">
            <div class="col-lg-12">
                <label>Select Weekly Holidays: </label><br>
                <?php if( isset($activity_leave_days[0]) ):?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="0" checked="checked" /> Sunday </label>
                <?php else:?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="0" /> Sunday </label>
                <?php endif;?>
                <?php if( isset($activity_leave_days[1]) ):?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="1" checked="checked" /> Monday </label>
                <?php else:?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="1" /> Monday </label>
                <?php endif;?>
                <?php if( isset($activity_leave_days[2]) ):?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="2" checked="checked" /> Tuesday </label>
                <?php else:?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="2" /> Tuesday </label>
                <?php endif;?>
                <?php if( isset($activity_leave_days[3]) ):?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="3" checked="checked" /> Wednesday </label>
                <?php else:?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="3" /> Wednesday </label>
                <?php endif;?>
                <?php if( isset($activity_leave_days[4]) ):?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="4" checked="checked" /> Thursday </label>
                <?php else:?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="4" /> Thursday </label>
                <?php endif;?>
                <?php if( isset($activity_leave_days[5]) ):?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="5" checked="checked" /> Friday </label>
                <?php else:?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="5" /> Friday </label>
                <?php endif;?>
                <?php if( isset($activity_leave_days[6]) ):?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="6" checked="checked" /> Saturday </label>
                <?php else:?>
                    <label><input type="checkbox" name = "activity_leave_days[]" value="6" /> Saturday </label>
                <?php endif;?>
            </div>
        </div>

        <button class="btn btn-info span12" type="submit">Update</button>
        <a href="<?php echo site_url('admin/leave'); ?>" class="btn btn-danger span12 upd-cancel">Cancel</a>
    </form>
      </div>
    </div>
</body>
</html>
