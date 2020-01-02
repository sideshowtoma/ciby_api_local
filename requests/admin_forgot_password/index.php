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

$email_address=null;

//check post
if( isset($_GET['email_address']) &&  isset($_GET['email_address'])  )
{
    $email_address=trim($_GET['email_address']);
}


//check get
if( isset($_POST['email_address']) &&  isset($_POST['email_address'])  )
{
    $email_address=trim($_POST['email_address']);
}


if(isset($email_address)  && !empty($email_address)  )
{
    
     //check
    $check=CheckIfExistsTwoColumnsFunction(admins_info_table_name,'email_address','email_address',$email_address,$email_address);
    
      
    if($check==false)//exists
    {
        $key_check=true;
        
        while ($key_check==true) 
        {
            $key=make_a_reset_key($email_address);
            if(!isset($_SESSION[md5($key)]))
            {
                $key_check=false;//exit loop
            }
        }
        
        $did_it_update=UpdateTableOneCondition(admins_info_table_name, 'password', 'on_reset', 'email_address', $email_address);//make it impossible to login 
                   
                    
        if($did_it_update==true)
        {

           
            
           //get the items in the table and set the session
            $admin_info=SelectTableOnTwoConditions(admins_info_table_name,'email_address',$email_address,'email_address',$email_address)[0];
            $_SESSION[md5($key)]=$admin_info;
            // echo  json_encode(array("key"=>$key,"cookie"=>"PHPSESSID=".session_id(),"_SESSION"=>$_SESSION[md5($key)],));
           //echo json_encode($_SESSION[$key]);
            $url=admin_recover_password."?PHPSESSID=".session_id()."&key=".$key;
            
            
                    $body= make_user_get_password_reset_url_email_html($admin_info['name'], $url);
                    mail_sender_function($admin_info['email_address'],$admin_info['name'],$body,'Please do not reply to this email.','Ciby platform password reset','../../downloads/mailer.txt',null);
                    
                    $response= json_encode(array("check"=>false,"message"=>"Successful, the reset website link has been sent to your email address."));
            
        }
        else
        {
            $response= json_encode(array("check"=>false,"message"=>"Could recover password at this time."));
        }
                             
        
        
        
       
       
    }
    else
    {
        $response= json_encode(array("check"=>false,"session_id"=>false,"message"=>"Wrong email address." ));
    }
}
else
{
    $response= json_encode(array("check"=>false,"session_id"=>false,"message"=>"Please provide email address."));
    
}

 echo $response;