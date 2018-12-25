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
        $config = array(
          'protocol' => 'smtp',
          'smtp_host' => 'ssl://smtp.gmail.com',
          'smtp_port' => 465,
          'smtp_user' => 'svivek908@gmail.com',
          'smtp_pass' => '9575780165',
          'mailtype' => 'html',
          'charset' => 'iso-8859-1',
          'wordwrap' => TRUE
        );
        $this->load->library('email', $config);
        $this->email->initialize($config); // Add
        $this->email->from('svivek908@gmail.com');
        $this->email->to('viveksharma826@gmail.com');
        $this->email->subject('testing');
       $message = '<p> confim mail sharpaly <a href="' . "<?php echo base_url('user/verify/')?>".$useremail.'/'.'">click here</a></p>';
        $this->email->message($message);
        $this->email->send()
        $this->User_model->verifyEmail($useremail);
        if ($this->email->send())
          {
          echo 'Email sent.';
          }
          else
          {
          print_r($this->email->print_debugger());
          }
        }
      }
    }
  }

