<?php 

class image extends CI_Controller 
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->output->set_header('Pragma: no-cache');  
     }

    function index()
    {
        $id = $this->input->get('activity_id');
        
        $this->load->model('admin/image_model');
        $images = $this->image_model->getallimages($id);
        $this->load->view('admin/header');        
        $this->load->view('admin/images/list',array('images'=>$images,'id' =>$id));
    }
    
    function imagerequired()
    {
        if ( $_FILES AND $_FILES['image']['name'] )
        {
            $activity_id = $this->session->userdata('activity_id');
            if (!is_dir('./img/activities/' . $activity_id))
            {
                mkdir('./img/activities/' . $activity_id, 0777, TRUE);
            }
            
            $config['upload_path'] = './img/activities/'.$activity_id.'/';
            $config['allowed_types'] = 'jpg|png|jpeg|jpe';
            $config['max_size'] = '3000';
            $config['encrypt_name'] = TRUE;
                        
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image'))
            {
                $error = $this->upload->display_errors();
                $this->form_validation->set_message('imagerequired', $error);
                return false;
            }
            else
            {
                $uploadDetails = $this->upload->data();
                return true;
            }
            
        }
        else
        {
            $this->form_validation->set_message('imagerequired', 'You must select an image to upload');
            return false;
        }
    }

    function backgroundrequired()
    {     
        if ( $_FILES AND $_FILES['image']['name'] )
        {
            print_r('success');
            if (!is_dir('./img/background/'))
            {
                mkdir('./img/background/', 0777, TRUE);
            }
            
            $config['upload_path'] = './img/background/';
            $config['allowed_types'] = 'jpg|png|jpeg|jpe';
            $config['max_size'] = '1024';
            $config['encrypt_name'] = TRUE;
                        
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image'))
            {
                $error = $this->upload->display_errors();
                $this->form_validation->set_message('backgroundRequired', $error);
                return false;
            }
            else
            {
                $uploadDetails = $this->upload->data();
                return true;
            }
        }
        else
        {
            print_r('failure');
            $this->form_validation->set_message('backgroundRequired', 'You must select an image to upload');
            return false;
        }
    }
   
    
    
    function add_image()
    {
        $this->form_validation->set_rules('image', 'Image', 'callback_imagerequired');
        $id = $this->input->get('activity_id'); 
        $edit = $this->input->get('id');      
        if($this->form_validation->run() == FALSE)
        {
            if($id)
            {
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Image is required</h6></div>');
                redirect('admin/image?activity_id='.$id);
            }
            else
            {
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Image is required</h6></div>');
                redirect('admin/flight/add_background');
            }
        }
        else
        {
            if($id)
            {
                $uploadDetails = $this->upload->data();
                $data['file_name'] = strtolower($uploadDetails['file_name']);
                $data['activity_id'] = $id;
                
                $this->load->model('admin/image_model');
                $inserted_image_id = $this->image_model->add_image($data);
                if($inserted_image_id)
                {
                    $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Image added successfully.</h6></div>');
                    redirect('admin/image?activity_id='.$id);
                }
                else
                {
                    $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Image not added! Please try again later.</h6></div>');
                    redirect('admin/activity');
                }
            }
            else if($edit)
            {
                $uploadDetails = $this->upload->data();
                $data['image'] = strtolower($uploadDetails['file_name']);
                
                $this->load->model('admin/image_model');
                $inserted_image_id = $this->image_model->edit_flight_image($data,$edit);
                if($inserted_image_id)
                {
                    $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>image  added successfully.</h6></div>');
                    $this->load->view('admin/flights/admin_background_create_text',array('data' => $edit));
                }
                else
                {
                    $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Image not added! Please try again later.</h6></div>');
                    redirect('admin/flight/list_background');
                }
            }
            else
            {
                $uploadDetails = $this->upload->data();
                $data['image'] = strtolower($uploadDetails['file_name']);
                
                $this->load->model('admin/image_model');
                $inserted_image_id = $this->image_model->add_flight_image($data);
                if($inserted_image_id)
                {
                    $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>image  added successfully.</h6></div>');
                    $this->load->view('admin/flights/admin_background_create_text',array('data' => $inserted_image_id));
                }
                else
                {
                    $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Image not added! Please try again later.</h6></div>');
                    redirect('admin/flight/list_background');
                }
            }
        }
    }

    function add_background_image(){
        $this->form_validation->set_rules('bg_image', 'Image', 'callback_imagerequired');
        //print_r('it success');die;
        if($this->form_validation->run() == FALSE){
            print_r('validation fails');die;
            //$this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Image is required</h6></div>');
            //redirect('admin/flight/add_background');
        }else{
            print_r('success');die;
            $uploadDetails = $this->upload->data();
            $data['file_name'] = strtolower($uploadDetails['file_name']);

            print_r($uploadDetails);die;
            
            $this->load->model('admin/image_model');
            $inserted_image_id = $this->image_model->add_image($data);
            if($inserted_image_id)
            {
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>image  added successfully.</h6></div>');
                redirect('admin/flight/add_background');
            }
            else
            {
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Image not added! Please try again later.</h6></div>');
                redirect('admin/flight/list_background');
            }
        }
    }
    
    function delete()
    {
        $id = $this->input->get('id');
       
       
        $this->load->model('admin/image_model');
        
        $image = $this->image_model->getimage($id);
        $activity_id =$image->activity_id;
//        print_r($activity_id);die;
        if($image)
        {
            if($this->image_model->deletebyid($id))
            {
                //Deleting all corresponding newsfeed & notifications.
                //$this->image_model->deleteNewsfeedForImage($id);
                //$this->image_model->deleteNotificationsForImage($id);
                 $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Image deleted successfully.</h6></div>');

                //########################################
                //-------------Delete From S3--------------- 
                /*$this->load->library('s3');
                $this->config->load('s3');
                $accessKey = $this->config->item('access_key');
                $secretKey = $this->config->item('secret_key');
                $useSSL = $this->config->item('use_ssl');
                $bucket_name = $this->config->item('bucket_name');
                
                $s3 = new S3($accessKey, $secretKey, $useSSL); 
                
                $s3_file_location = 'images/store/images/'.$image->store_id.'/'.$image->image;
                
                if ($s3->deleteObject($bucket_name, $s3_file_location)) { 
                    $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Image deleted successfully.</h6></div>');
                }*/
                //###########################################
                //--------------Delete from Cloudinary-------
                
              /*  require './application/libraries/cloudinary/Cloudinary.php';
                require './application/libraries/cloudinary/Uploader.php';
                require './application/libraries/cloudinary/Api.php';
                
                $this->config->load('cloudinary');
                $cloud_name = $this->config->item('cloud_name');
                $api_key = $this->config->item('api_key');
                $api_secret = $this->config->item('api_secret');
                
                \Cloudinary::config(array( 
                                            "cloud_name" => $cloud_name, 
                                            "api_key" => $api_key, 
                                            "api_secret" => $api_secret 
                                            ));
                
                $cloudinary_file_location = 'images/store/images/'.$image->store_id.'/'.$image->image;
                
                if(\Cloudinary\Uploader::destroy($cloudinary_file_location, array("invalidate" => TRUE)))
                {
                    $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Image deleted successfully.</h6></div>');
                }
                else
                {
                    $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>Image deleted successfully. Residue files on S3.</h6></div>');
                }*/
            }
            else
                $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Image not deleted! Please try again later.</h6></div>');
        }
        else
        {
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Invalid Image ID.</h6></div>');
        }
        
        redirect('admin/image?activity_id='.$activity_id);           
    }   
    
    function mainimage()
    {
        $id = $this->input->get('id');
        $this->load->model('admin/image_model');
        $this->image_model->set_is_main_indicator($id);
        $activity_id = $this->image_model->getimageactivityid($id);
        
        $data['activity_main_image'] = $this->input->get('filename');
        $this->load->model('admin/activity_model');
        
        
        $image = $this->activity_model->updatemainimage($data,$activity_id);
        if($image)
        {
           $this->session->set_flashdata('returnMsg', '<div class="alert alert-success" ><h6>MainImage is set successfully.</h6></div>');  
        }
        else
        {
            $this->session->set_flashdata('returnMsg', '<div class="alert alert-danger" ><h6>Couldnot set as main image! Please try again.</h6></div>'); 
        }

       
         redirect('admin/image?activity_id='.$activity_id);    
    }

    
    
}  
    
    ?>