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
 
$session_id=null;
$organization_id= null;
$email_address=null;
$name= null;
$telephone_number= null;


//check post
if( isset($_GET['session_id']) &&  !empty($_GET['session_id'])  &&
    isset($_GET['organization_id']) &&  !empty($_GET['organization_id'])  &&
    isset($_GET['email_address']) &&  !empty($_GET['email_address'])  &&  
    isset($_GET['name']) &&  !empty($_GET['name'])   &&
    isset($_GET['telephone_number']) &&  !empty($_GET['telephone_number'])  
       
        )
{
    $session_id=trim($_GET['session_id']);
    $organization_id=trim($_GET['organization_id']);
    $email_address=trim($_GET['email_address']);
    $name=trim($_GET['name']);
    $telephone_number=trim($_GET['telephone_number']);
    
}


//check get
if( isset($_POST['session_id']) &&  !empty($_POST['session_id'])  &&
    isset($_POST['organization_id']) &&  !empty($_POST['organization_id'])  &&
    isset($_POST['email_address']) &&  !empty($_POST['email_address'])  &&  
    isset($_POST['name']) &&  !empty($_POST['name'])   &&
    isset($_POST['telephone_number']) &&  !empty($_POST['telephone_number'])  
       
        )
{
    $session_id=trim($_POST['session_id']);
    $organization_id=trim($_POST['organization_id']);
    $email_address=trim($_POST['email_address']);
    $name=trim($_POST['name']);
    $telephone_number=trim($_POST['telephone_number']);
    
}


if(isset($session_id) && !empty($session_id)  &&
    isset($organization_id) && !empty($organization_id)  &&
    isset($email_address) && !empty($email_address)  &&
    isset($name) && !empty($name) &&
    isset($telephone_number) && !empty($telephone_number) 
    )
{
    
     //check if session is set
    if( isset($_SESSION[$session_id]) &&  !empty($_SESSION[$session_id]) )
    {
        $read_session=$_SESSION[$session_id];
        
             
        $check_orginization= CheckIfExistsTwoColumnsFunction(organization_info_table_name, '_id', '_id', $organization_id, $organization_id);
        $check_exists_email= CheckIfExistsTwoColumnsFunction(admins_info_table_name, 'email_address', 'email_address', $email_address, $email_address);
        
        if(
            $check_orginization==false &&
            $check_exists_email==true 
                )//no match
        {
            
                if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) 
                {
                    $password=generateStrongPassword(default_password_size,false, 'luds');
                    $body= make_user_created_invitational_email_html($name, $email_address, $password, admin_login_url);
                    
                    mail_sender_function($email_address,$name,$body,'Please do not reply to this email.','Ciby platform sign up email','../../downloads/mailer.txt',null);
                       
                                    //insert
                                 $insert= InsertIntoAdminInfoTable(admins_info_table_name, $organization_id, $email_address, $name, $telephone_number, md5($password), storable_datetime_function(time())) ;
                                        
                                    if($insert==true)
                                    {
                                        $response= json_encode(array("check"=>true,"message"=>"Successful."));
                                    }
                                    else
                                    {
                                         $response= json_encode(array("check"=>false,"message"=>"Could not create admin at this time."));
                            
                                    }

                        
                }
                else
                {
                    $response= json_encode(array("check"=>false,"message"=>"Invalid email address."));
                }
    
           
        
        }
        else
        {
            $response= json_encode(array("check"=>false,"message"=>"Organization does not exist or Email address is already in use."));
        }
        
   
    }
    else 
    {
        $response= json_encode(array("check"=>false,"message"=>"Please provide a valid session id."));
    }
}
else
{
    $response= json_encode(array("check"=>false,"message"=>"Please fill all the required fields."));
    
}

echo $response;
 
/*
  json_encode(array(
     "1"=>array( "branch_id"=>"1","branch_name"=>"branch name 1","country"=>"Kenya","state_county_province"=>"state_county_province 1","location"=>"place 1","directions"=>"directions 1","co-ordinates"=>array(),"phone_contacts"=>array("0716214868","0716214868"),"email_contacts"=>array("info@clicksoft.co.ke","info@clicksoft.co.ke")),
     "2"=>array( "branch_id"=>"2","country"=>"Kenya","state_county_province"=>"state_county_province 2","location"=>"place 2","directions"=>"directions 2","co-ordinates"=>array(),"phone_contacts"=>array("0716214868","0716214868"),"email_contacts"=>array("info@clicksoft.co.ke","info@clicksoft.co.ke")),
        
 ));
  */
