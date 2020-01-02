<?php
require '../../functions/session.php';
require '../../functions/constants.php';
require '../../functions/functions.php';
require '../../functions/database.php';

require_once('../../functions/phpmailer/class.phpmailer.php');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//check get or post

$key=null;
$new_password=null;
$confirm_password=null;

//check post
if( isset($_GET['key']) &&  !empty($_GET['key'])  &&
    isset($_GET['new_password']) &&  !empty($_GET['new_password']) &&
    isset($_GET['confirm_password']) &&  !empty($_GET['confirm_password'])
        )
{
    $key=trim($_GET['key']);
    $new_password=trim($_GET['new_password']);
    $confirm_password=trim($_GET['confirm_password']);
}


//check get
if( isset($_POST['key']) &&  !empty($_POST['key'])   &&
    isset($_POST['new_password']) &&  !empty($_POST['new_password']) &&
    isset($_POST['confirm_password']) &&  !empty($_POST['confirm_password'])
        )
{
    $key=trim($_POST['key']);
    $new_password=trim($_POST['new_password']);
    $confirm_password=trim($_POST['confirm_password']);
}
//echo $key;
//echo json_encode($_SESSION[md5($key)]);
if(isset($key) && !empty($key)  &&     
    isset($new_password) && !empty($new_password) &&
    isset($confirm_password) && !empty($confirm_password) 
        )
{
     //check if session is set
    if( isset($_SESSION[md5($key)]) &&  !empty($_SESSION[md5($key)]) )
    {
        
        $admin_info=$_SESSION[md5($key)];
       
       
            if($new_password==$confirm_password)//check the two new passwords match
            {
                if(preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/',$new_password) && preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $new_password)  && strlen($new_password)>=7 && strlen($new_password)<=15)
                {
                    //update
                    $did_it_update=UpdateTableOneCondition(admins_info_table_name, 'password', md5($new_password), '_id', $admin_info['_id']);
                    if($did_it_update==true)
                    {
                            $body= make_user_get_password_recover_url_email_html($admin_info['name']);
                            mail_sender_function($admin_info['email_address'],$admin_info['name'],$body,'Please do not reply to this email.','Ciby platform password reset confirmation','../../downloads/mailer.txt',null);

                          
                       
                         $response= json_encode(array("check"=>true,"message"=>"Success. please log in with your new password."));
                         unset($_SESSION[md5($key)]);
                    }
                    else
                    {
                         $response= json_encode(array("check"=>false,"message"=>"Could not update password at this time."));
                    }
                    
                }
                else
                {
                     $response= json_encode(array("check"=>false,"message"=>"Your password must be more than 7 and less than 15 characters long and must contain letters, numbers and at least one special character."));
                }
            }
            else
            {
                $response= json_encode(array("check"=>false,"message"=>"Your two new passwords do not match."));
            }
       
        
        
    }
    else 
    {
        $response= json_encode(array("check"=>false,"message"=>"Please provide a valid key."));
    }
}
else
{
    $response= json_encode(array("check"=>false,"message"=>"Please provide a key."));
    
}

echo $response;
 
/*
  json_encode(array(
     "1"=>array( "branch_id"=>"1","branch_name"=>"branch name 1","country"=>"Kenya","state_county_province"=>"state_county_province 1","location"=>"place 1","directions"=>"directions 1","co-ordinates"=>array(),"phone_contacts"=>array("0716214868","0716214868"),"email_contacts"=>array("info@clicksoft.co.ke","info@clicksoft.co.ke")),
     "2"=>array( "branch_id"=>"2","country"=>"Kenya","state_county_province"=>"state_county_province 2","location"=>"place 2","directions"=>"directions 2","co-ordinates"=>array(),"phone_contacts"=>array("0716214868","0716214868"),"email_contacts"=>array("info@clicksoft.co.ke","info@clicksoft.co.ke")),
        
 ));
  */
