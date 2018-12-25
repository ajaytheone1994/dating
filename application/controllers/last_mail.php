<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller

  {
  function __construct()
    {
    parent::__construct();
    $this->load->library('session');
    $this->load->library('email');
    $this->load->database();
    $this->load->model('User_model');
    }

  public

  function index()
    {
    $response['errorCode'] = 1;
    $response['message'] = "Please try it again later";
    $response['data'] = array();
    $useremail = $this->input->post('user_email');
    $userPassword = $this->input->post('user_pasword');

    // $userage = $this->input->post('user_age');

    /*  $userdob = $this->input->post('user_dob');
    $userlanguage = $this->input->post('user_language');
    $userlocation = $this->input->post('user_location');
    $userrelation = $this->input->post('user_relation');
    $userinvitecode = $this->input->post('user_invite_code');*/
    if (empty($useremail))
      {
      $response['message'] = "Please Insert email";
      echo json_encode($response);
      exit;
      }

    // if($this->User_model->Exist(array('user_email'=>$useremail))){$response['message'] = "Email Already Exist";echo json_encode($response);exit;}

    if (empty($userPassword))
      {
      $response['message'] = "Please Insert password";
      echo json_encode($response);
      exit;
      }

    // if(empty($userage)){$response['message'] = "Please Insert age";echo json_encode($response);exit;}

    /*if(empty($userdob)){$response['message'] = "Please Insert date of birth";echo json_encode($response);exit;}

    if(empty($userlanguage)){$response['message'] = "Please Insert language";echo json_encode($response);exit;}

    if(empty($userlocation)){$response['message'] = "Please Insert location";echo json_encode($response);exit;}

    if(empty($userrelation)){$response['message'] = "Please Insert relation";echo json_encode($response);exit;}

    if(empty($userinvitecode)){$response['message'] = "Please Insert invite code";echo json_encode($response);exit;}

    $userPassword = base64_encode($userPassword);*/
    $data = array(
      'user_email' => $useremail,
      'user_pasword' => $userPassword,
      'status' => 0,

      // 'user_age' => $userage,

      /*'user_dob' => $userdob,
      'user_language' => $userlanguage,
      'user_location' => $userlocation,
      'user_relation' => $userrelation,
      'user_invite_code' => $userinvitecode,*/
    );
    $result = $this->User_model->insert($data);
    if ($result)
      {
      $response['errorCode'] = 0;
      $response['message'] = "User Data Saved Successfully";
      if ($this->User_model->Exist(array(
        'user_email' => $useremail
      )))
        {
        $response['message'] = "email exit";
        json_encode($response);
        /*mail send*/
        /* $data=array(
        $email = $useremail
        );*/
        $content ='<html>
        <center>
          <h1 style="color:green;background:#f1f1f1;padding:10px;"> Please Activate Your Email </h1>
         <h3> Confim Mail Sharpaly </h3>
         <a style="background:blue;color:#fff;padding:10px;border-radius:10%;text-decoration:none;" href="' . "<?php echo base_url('user/verify/')?>".$useremail.'/'.'">click here</a>
           </center> 
        </html>';
        $body = $content;
         echo$body;exit;
        $from ='svivek908@gmail.com.com';
        $subject = "Activate ".$useremail."";
        $server=$_SERVER['HTTP_HOST'];
        $headers = "From: P3DATING <".$from. ">\r\nContent-type: text/html; charset=iso-8859-1\r\nMIME-        Version: 1.0\r\n";
        $to = $useremail;
        $send_email = mail($to, $subject, $body, $headers);
        $b= "Password containing mail sent..";
         $response=$this->User_model->verifyEmail($useremail);
         
        echo json_encode($response);
          
        }else
{
    $b= "User Record Not Found For this email..";
    echo json_encode($b);
}
      }
    }
  }

