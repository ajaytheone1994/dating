<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
  function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('email');
        $this->load->database();
        $this->load->model('User_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('upload');//upload class initialized
        
    }

  public function index()
  {
      $response['errorCode'] = 1;
      $response['message'] = "Please try it again later";
      $response['data'] = array();

      $useremail = $this->input->post('user_email');
      $userPassword = $this->input->post('user_pasword');
      //$userage = $this->input->post('user_age');
    /*  $userdob = $this->input->post('user_dob');
      $userlanguage = $this->input->post('user_language');
      $userlocation = $this->input->post('user_location');   
      $userrelation = $this->input->post('user_relation');
      $userinvitecode = $this->input->post('user_invite_code');*/

       if(empty($useremail)){$response['message'] = "Please Insert email";echo json_encode($response);exit;}
        //if($this->User_model->Exist(array('user_email'=>$useremail))){$response['message'] = "Email Already Exist";echo json_encode($response);exit;}
       if(empty($userPassword)){$response['message'] = "Please Insert password";echo json_encode($response);exit;}
      // if(empty($userage)){$response['message'] = "Please Insert age";echo json_encode($response);exit;}
       /*if(empty($userdob)){$response['message'] = "Please Insert date of birth";echo json_encode($response);exit;}
       if(empty($userlanguage)){$response['message'] = "Please Insert language";echo json_encode($response);exit;}
       if(empty($userlocation)){$response['message'] = "Please Insert location";echo json_encode($response);exit;}
       if(empty($userrelation)){$response['message'] = "Please Insert relation";echo json_encode($response);exit;}
       if(empty($userinvitecode)){$response['message'] = "Please Insert invite code";echo json_encode($response);exit;}
       $userPassword = base64_encode($userPassword);*/
        $data   = array(
            'user_email' => $useremail,
            'user_pasword' => $userPassword,
            'user_verify'      =>0,
           //'user_age' => $userage,
           /*'user_dob' => $userdob,
            'user_language' => $userlanguage,
           'user_location' => $userlocation,
           'user_relation' => $userrelation,
           'user_invite_code' => $userinvitecode,*/
           
            
        );
    $result=$this->User_model->insert($data);

    if($result){

      $response['errorCode'] = 0;
            $response['message']   = "User Data Saved Successfully";

            if ($this->User_model->Exist(array('user_email'=>$useremail))) {

              $response['message']   = "email exit";
              $data   = array(
            'user_email' => $useremail,
            'user_pasword' => $userPassword,
           
           
            
        );
              $e=$this->User_model->send_validation_email($data) ;
             
   

    
   $config = Array( 
  'protocol' => 'smtp', 
  'smtp_host' => 'localhost', 
  'smtp_port' => 25, 
  'smtp_user' => 'svivek908@gmail.com', 
  'smtp_pass' => 'password',
   'mailtype'  => 'html', 
   
    'wordwrap' => TRUE
); 
 
  $this->load->library('email',$config); 
  $this->email->set_newline("\r\n");
   
  $this->email->set_header('Content-type', 'text/html');   
  $this->email->from('svivek908@gmail.com', 'vivek');
  $this->email->to($useremail);
   $this->email->subject(' My mail through codeigniter from localhost ');
 
  $this->email->message('<h1>confirm email</h1><p> confim mail sharpaly <a href="' . "<?php echo base_url('user/verify/')?>".$useremail.'/'.'">click here</a></p>');
  if (!$this->email->send()) {
    show_error($this->email->print_debugger()); }
  else {
    echo 'Your e-mail has been sent!';
    
  }}}}
  // Check for user login process
  //login//
  public function login()
    {
        $response['errorCode'] = 1;
        $response['message'] = "Please try it again later";
        $response['data'] = array();
  
        $userEmail = $this->input->post('user_email');
        $userPassword = $this->input->post('user_pasword');
        $isWeb = $this->input->post('isweb');

        if(empty($userEmail)){$response['message'] = "Please Insert Email";echo json_encode($response);exit;}
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {$response['message'] = "Please Insert Valid Email";echo json_encode($response);exit;}
        if(empty($userPassword)){$response['message'] = "Please Insert Password";echo json_encode($response);exit;}
        $userPassword = base64_encode($userPassword);

        $data   = array(            
            'user_email' => $userEmail,
            'user_pasword' => $userPassword
        );
        $result = $this->User_model->login($data);
        if($result){     
            if(!isset($isWeb)){
                $dataToken   = array(            
                  'access_token' => base64_encode($userEmail.'__'.time())
                );
                $result = $this->User_model->updateToken($dataToken, $result[0]->user_id,$data);       
            }
            $response['errorCode'] = 0;
            $response['message']   = "User Logged-In Successfully";
            $response['data'] = $result;
        }else{
            $response['message'] = "User Login Failed";
        }
        echo json_encode($response);
        exit;
    }


public function forgotpassword()  
    {
        $response['errorCode'] = 1;
        $response['message'] = "Please try it again later";
        $response['data'] = array();
  
        $userEmail = $this->input->post('user_email');

        if(empty($userEmail)){$response['message'] = "Please Insert Email";echo json_encode($response);exit;}
       /* if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {$response['message'] = "Please Insert Valid Email";echo json_encode($response);exit;}*/
        $userData = $this->User_model->Exist(array('user_email'=>$userEmail));
		
        if(!$userData){$response['message'] = "Email Not Exist In The System";echo json_encode($response);exit;}

        //Load email library
        $this->load->library('email');
        $this->email->from("svivek908@gmail.com", 'Blue T Wine');
        $this->email->to($userEmail);
        $this->email->subject('P3Date Forgot Password');
 
        $this->email->message( 'Hello, Here is your password: '.$userData[0]->user_pasword);
        //Send mail
        if($this->email->send()){            
            $response['errorCode'] = 0;
            $response['message']   = "Forgot Password Email Sent Successfully";
        }else{
            $response['message']   = "Forgot Password Email Sending Failed";
        }
        echo json_encode($response);
        exit;
    }



    //search api
     public function usersearch(){

        $response['errorCode'] = 1;
        $response['message'] = "Please try it again later";
        $response['data'] = array();     

        $searchString = $this->input->post('search_string');
        $accessToken = $this->input->post('access_token');
        
         

        if(empty($accessToken)){$response['message'] = "Please Insert Access Token";echo json_encode($response);
        $userData = $this->User_model->getUserIdByToken($accessToken);
        if(!$userData){$response['message'] = "Please Insert Valid Access Token";echo json_encode($response);exit;}
        if(empty($searchString)){$response['message'] = "Please Insert Search String";echo json_encode($response);exit;}

        $responseData = $this->User_model->getUserSearch($searchString);
        if($responseData){            
            $response['errorCode'] = 0;
            $response['message']   = "Get User Search Data Successfully";
            $response['data']   = $responseData;
        }else{
            $response['message']   = "No User Search Data Found";
        }
        echo json_encode($response);
        
    }}
    //image_upload api
    // public function ImgUpload(){

    //     $response['errorCode'] = 1;
    //     $response['message'] = "Please try it again later";
    //     $response['data'] = array();     

    //     $Pic = $this->input->post('image');
         
    //     if(empty($Pic)){$response['message']="please insert your profle_pic";  
    //upload users_images
  
  public function uplaodpassportimages(){

        $response['errorCode'] = 1;
        $response['message'] = "Please try it again later";
        $response['data'] = array();
        $accessToken = $this->input->post('access_token');
        $passportImages1 = !empty($_FILES['image'])?$_FILES['image']:'';
     if(empty($accessToken)){$response['message'] = "Please Insert Access Token";echo json_encode($response);exit;}
        $userData = $this->User_model->getUserIdByToken($accessToken);
//If set to true, if a file with the same name as the one you are uploading exists, it will be overwritten

        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name']     =  '_'.rand(1,time());
        $config['overwrite']     = TRUE;
        $config['max_size']      = 10000;

        // Alternately you can set preferences by calling the ``initialize()`` method. Useful if you auto-load the class:
        $this->upload->initialize($config);
        
        if (!$this->upload->do_upload('image')) {
            $response['message'] = "Please Insert Valid Profile Image";echo json_encode($response);exit;
        }

        $img = $this->upload->data();
        $profileimage = ($img['is_image']==true && !empty($img['file_name'])) ? $img['file_name'] : '';
        //var_dump($profileimage);exit;
        if (empty($profileimage)) {
            $response['message'] = "Profile Image Corrupted";echo json_encode($response);exit;
        }

        $data   = array(
            'user_id' => !empty($userData[0]->user_id)?$userData[0]->user_id:0,
            'image' => '/uploads/'.$profileimage,

            
        ); $result = $this->User_model->insertimage($data);
        if($result){
            $response['errorCode'] = 0;
            $response['message']   = " User Data Saved Successfully";
            
        }else{
            $response['message'] = "Social User Data Saving Failed";
        }
        echo json_encode($response);
        exit;
    } 
    //i am men looking for women.....
  public function Useris(){
            $response['error code']=0;
            $response['message']='plz try it again later';
            $response['data']=array();
            $gender=$this->input->post('gender');
            
            $looking=$this->input->post('looking_for');
            $accessToken=$this->input->post('access_token');

          if(empty($gender)){$response['message'] = "Please Insert gender";echo json_encode($response);exit;}
          if(empty($looking)){$response['message']='plz insert looking';echo json_encode($response);exit;}
          if(empty($accessToken)){$response['message']='plz access_token';echo json_encode($response);exit;}
    


    $userData = $this->User_model->getUserIdByTokenn($accessToken);
    $data   = array(
            'user_id' => !empty($userData[0]->user_id)?$userData[0]->user_id:0,
            'gender' => $gender,
            'looking_for'=>$looking,

            
        ); $result = $this->User_model->insertgender($data);
        if($result){
            $response['errorCode'] = 1;
            $response['message']   = " User Data Saved Successfully";
            
        }else{
            $response['message'] = "User Data Saving Failed";
        }
        echo json_encode($response);
        exit;
    }  
    
}



    


       
