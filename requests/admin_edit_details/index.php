<?php
require '../../functions/session.php';
require '../../functions/constants.php';
require '../../functions/functions.php';
require '../../functions/database.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//check get or post

$session_id=null;
$email_address=null;
$telephone_number= null;
$name=null;

//check post
if( isset($_GET['session_id']) &&  !empty($_GET['session_id'])  &&
    isset($_GET['email_address']) &&  !empty($_GET['email_address'])  &&  
    isset($_GET['telephone_number']) &&  !empty($_GET['telephone_number'])  && 
    isset($_GET['name']) &&  !empty($_GET['name'])   
        )
{
    $session_id=trim($_GET['session_id']);
    $email_address=trim($_GET['email_address']);
    $telephone_number=trim($_GET['telephone_number']);
    $name=trim($_GET['name']);
    
}


//check get
if( isset($_POST['session_id']) &&  !empty($_POST['session_id'])  &&
    isset($_POST['email_address']) &&  !empty($_POST['email_address'])  &&  
    isset($_POST['telephone_number']) &&  !empty($_POST['telephone_number'])  && 
    isset($_POST['name']) &&  !empty($_POST['name'])   
        )
{
    $session_id=trim($_POST['session_id']);
    $email_address=trim($_POST['email_address']);
    $telephone_number=trim($_POST['telephone_number']);
    $name=trim($_POST['name']);
    
}


if(isset($session_id) && !empty($session_id)  &&
   isset($email_address) && !empty($email_address)  &&
    isset($telephone_number) && !empty($telephone_number)  &&
    isset($name) && !empty($name)  
        )
{
    
     //check if session is set
    if( isset($_SESSION[$session_id]) &&  !empty($_SESSION[$session_id]) )
    {
        
        if(filter_var($email_address, FILTER_VALIDATE_EMAIL)) 
        {
                //check if branch id exists in branch
                $read_session=$_SESSION[$session_id];
              
                $_id=$read_session['_id'];
                
                 $check_email_valid=check_that_only_the_admin_of_the_email_has_that_email_or_no_one_has_it($email_address,$_id);
                 if($check_email_valid==true)
                 {
                                

                                            
                     
                             //update
                             $update=UpdateAdminsTableOneCondition(admins_info_table_name,$email_address, $telephone_number,$name,'_id',$_id);
                             if($update==true)
                             {

                                 //update session
                                 $_SESSION[$session_id]['email_address']=$email_address;
                                 $_SESSION[$session_id]['telephone_number']=$telephone_number;
                                 $_SESSION[$session_id]['name']=$name;

                                 $response= json_encode(array("check"=>true,"message"=>"Successful."));
                             }
                             else
                             {
                                 $response= json_encode(array("check"=>false,"message"=>"Could not update details at this time."));
                             }
                                                                  
                                   
                               
                 }
                 else
                 {
                     $response= json_encode(array("check"=>false,"message"=>"Please provide an email address that is not in use."));
                 }
            
        }
        else
        {
             $response= json_encode(array("check"=>false,"message"=>"Please provide a valid email address."));
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
