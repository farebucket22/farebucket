<?php
class Activity_Model extends CI_Model{
  
    public function get_activity_countries(){
        $data = $this->db->get("farebucket_activity_country");
        return $data->result();
    }
    
    public function get_activity_cities($countryId){
        $data = $this->db->get_where("farebucket_activity_city", array('activity_country_id'=>$countryId));
        return $data->result();
    }
    
    public function get_activity_search_results($cityId){
        $this->db->select('*');			
        $this->db->join('farebucket_activity_category', 'farebucket_activity_category.activity_category_id = farebucket_activity.activity_category_id', 'left'); 
		$this->db->where('activity_city', $cityId);
        $this->db->order_by('activity_onwards_price','ASC');
		$data = $this->db->get('farebucket_activity');
        return $data->result();
    }
    
    public function get_activity_details($activityId) {
        $this->db->select('*');	
        $this->db->join('farebucket_activity_images', 'farebucket_activity_images.activity_id = farebucket_activity.activity_id', 'left'); 
        $data1 = $this->db->get_where("farebucket_activity", array('farebucket_activity.activity_id'=>$activityId));
        
        $this->db->select('*');	
        $this->db->join('farebucket_activity_rating', 'farebucket_activity_rating.user_id = farebucket_user.user_id', 'left'); 
        $data2 = $this->db->get_where("farebucket_user", array('farebucket_activity_rating.activity_id'=>$activityId));
        
        return array($data1->result(),$data2->result());
    }
    
    public function set_activity_user_review($rating, $comment, $activityId, $userId){
    	date_default_timezone_set("Asia/Kolkata");
    	$data = array(
    		'activity_id' => $activityId,
    		'user_id' => $userId,
    		'activity_rating_value' => $rating,
    		'activity_individual_comment' => $comment,
    		'created_time' => date("Y-m-d H:i:s")
    	);
    	
    	$this->db->insert("farebucket_activity_rating", $data);
    }

    public function get_activity_sub_type_details($activityId){
        $this->db->select('*');
        $data = $this->db->get_where("farebucket_activity_sub_type", array('activity_id'=>$activityId));
        return $data->result();
    }

    public function get_booking_availability($bookingDate, $activitySubTypeId){
        $this->db->select('*');
        $data = $this->db->get_where("farebucket_activity_booking", array('activity_booking_date'=>$bookingDate, 'activity_sub_type_id'=>$activitySubTypeId, 'booking_status !=' => "Payment Failed"));
        return $data->result();
    }
    
    public function get_leave_dates($activityId) {
        $this->db->select('*');
        $data = $this->db->get_where("farebucket_activity_leave", array('activity_id'=>$activityId));
        return $data->result();
    }
    
    public function set_booking_details($bookingDetails) {
        $this->db->insert("farebucket_activity_booking", $bookingDetails);
        return $this->db->insert_id();
    }
    
    public function update_booking_status($bookingId, $status){
        $data = array(
               'booking_status' => $status
            );

        $this->db->where('booking_id', $bookingId);
        $this->db->update('farebucket_activity_booking', $data); 
    }
    
    public function get_booking_details($userId) {
        $data = $this->db->get_where("farebucket_activity_booking", array('user_id'=>$userId));
        return $data->result();
    }

    public function get_ticket_by_user_id($userId){

    }

    public function get_ticket_by_booking_id($booking_id) {
        $data = $this->db->get_where("farebucket_activity_booking", array('booking_id'=>$booking_id));
        return $data->result();
    }

    public function get_activity_name_by_id($activity_id) {
        $data = $this->db->get_where("farebucket_activity", array('activity_id'=>$activity_id));
        return $data->result()[0]->activity_name;
    }

    public function get_subactivity_name_by_id($subactivity_id) {
        $data = $this->db->get_where("farebucket_activity_sub_type", array('activity_sub_type_id'=>$subactivity_id));
        return $data->result()[0]->activity_sub_type_name;
    }

    public function update_actvity_status($booking_id){
        $bookingdate = $this->db->query('select * from farebucket_activity_booking where booking_id='.intval($booking_id));
        $result = $bookingdate->result();
        $datetime = $result[0]->activity_booking_date.' 23:59:59';
        $booking_date =  strtotime('-1 day', strtotime($datetime));
        $awesome = date('m/d/Y H:i:s');
        $current_date =  strtotime($awesome);
        $dateResult = $booking_date - $current_date;
        $difference = ($dateResult/86400)*24;
        
        if($difference > 11)
        {
            if($this->db->update('farebucket_activity_booking',array('booking_status' => "Cancelled"),array('booking_id' => $booking_id)))
                return true;
        }
        else
        {
            return 0;
        }

    }
    
    public function check_discount_code($discountCode, $discountModule) {
        $data = $this->db->get_where("farebucket_discount_code", array('discount_code'=>$discountCode, 'discount_code_module'=>$discountModule));
        return $data->result();
    }

    public function add_guest($data) {
        $this->db->insert('farebucket_user',$data);
        return $this->db->insert_id();
    }

    public function get_booking_date() {
        $data = $this->db->query('select activity_booking_date from farebucket_activity_booking');
        return $data->result();
    }

    public function update_status_by_date($booking_date) {
        if($this->db->update('farebucket_activity_booking',array('booking_status' => "Closed"),array('activity_booking_date' => $booking_date))) {
            return true;
        }
        else {
            return false;
        }
    }

    public function get_vendor_name($id){
        $data = $this->db->get_where("farebucket_activity", array('activity_id'=>$id));
        if($data)
            return $data->result()[0]->activity_vendor_name;
        else
            return false;
    }

    public function getLastFbBookingId(){
        $data = $this->db->query('select fb_bookingId from farebucket_activity_booking order by booking_id desc limit 0,1');
        $res = $data->result();
        $fbId = ($res[0]->fb_bookingId);
        return $fbId;   
    }

    public function getActivityByUserDetails($data){
        $data = $this->db->get_where("farebucket_activity_booking", $data);
        if( $data->num_rows() > 0 )
            return $data->result();
        else
            return false;
    }

    public function updatePayuId($payu_id, $booking_id){
      $this->db->where('booking_id', $booking_id);
      if($this->db->update('farebucket_activity_booking', array('payu_id' => $payu_id)))
        return true;
      else
        return false;
    }

}
?>