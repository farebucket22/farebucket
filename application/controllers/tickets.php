<?php
@session_start();

class Tickets extends MY_controller{
	function guest_ticket()
    {
        $this->load->view('common/header.php');
        $this->load->view('flights/guest_dashboard.php');
        $this->load->view('common/footer.php');
    }
}
?>