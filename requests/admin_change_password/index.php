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
$old_password=null;
$new_password=null;
$confirm_password=null;

//check post
if( isset($_GET['session_id']) &&  !empty($_GET['session_id'])  &&
    isset($_GET['old_password']) &&  !empty($_GET['old_password']) &&
    isset($_GET['new_password']) &&  !empty($_GET['new_password']) &&
    isset($_GET['confirm_password']) &&  !empty($_GET['confirm_password'])
        
        )
{
    $session_id=trim($_GET['session_id']);
    $old_password=trim($_GET['old_password']);
    $new_password=trim($_GET['new_password']);
    $confirm_password=trim($_GET['confirm_password']);
}


//check get
if(  isset($_POST['session_id']) &&  !empty($_POST['session_id'])&&
    isset($_POST['old_password']) &&  !empty($_POST['old_password']) &&
    isset($_POST['new_password']) &&  !empty($_POST['new_password']) &&
    isset($_POST['confirm_password']) &&  !empty($_POST['confirm_password'])   
        )
{
    $session_id=trim($_POST['session_id']);
    $old_password=trim($_POST['old_password']);
    $new_password=trim($_POST['new_password']);
    $confirm_password=trim($_POST['confirm_password']);
}


if(isset($session_id) && !empty($session_id) &&
   isset($old_password) && !empty($old_password) &&     
    isset($new_password) && !empty($new_password) &&
    isset($confirm_password) && !empty($confirm_password) 
        )
{
     //check if session is set
    if( isset($_SESSION[$session_id]) &&  !empty($_SESSION[$session_id]) )
    {
        $read_session=$_SESSION[$session_id];
        $old_password_is=$read_session['password'];
        $_id=$read_session['_id'];
        
        if(md5($old_password)== strtolower($old_password_is) )//check old password matches
        {
            if($new_password==$confirm_password)//check the two new passwords match
            {
                if(preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/',$new_password) && preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $new_password)  && strlen($new_password)>=7 && strlen($new_password)<=15)
                {
                    //update
                    $did_it_update=UpdateTableOneCondition(admins_info_table_name, 'password', md5($new_password), '_id', $_id);
                    if($did_it_update==true)
                    {
                       
                         $response= json_encode(array("check"=>true,"message"=>"Success. please log back in with your new password."));
                          unset($_SESSION[$session_id]);
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
            $response= json_encode(array("check"=>false,"message"=>"Your current password does not match with what you have provided."));
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
