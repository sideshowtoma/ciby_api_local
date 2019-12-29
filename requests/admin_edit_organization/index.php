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
$organization_name= null;
$description= null;
$limited_number= null;
$kra_pin= null;
$county_id= null;
$email_address= null;
$phone_number= null;
$_id= null;


//check post
if( isset($_GET['session_id']) &&  !empty($_GET['session_id'])  &&
    isset($_GET['organization_name']) &&  !empty($_GET['organization_name'])  &&
    isset($_GET['description']) &&  !empty($_GET['description'])   &&
    isset($_GET['limited_number']) &&  !empty($_GET['limited_number'])  &&  
    isset($_GET['kra_pin']) &&  !empty($_GET['kra_pin'])   &&
    isset($_GET['county_id']) &&  !empty($_GET['county_id']) &&
    isset($_GET['email_address']) &&  !empty($_GET['email_address']) &&
    isset($_GET['phone_number']) &&  !empty($_GET['phone_number'])  &&
    isset($_GET['_id']) &&  !empty($_GET['_id']) 
       
        )
{
    $session_id=trim($_GET['session_id']);
    $organization_name=trim($_GET['organization_name']);
    $description=trim($_GET['description']);
    $limited_number=trim($_GET['limited_number']);
    $kra_pin=trim($_GET['kra_pin']);
    $county_id=trim($_GET['county_id']);
    $email_address=trim($_GET['email_address']);
    $phone_number=trim($_GET['phone_number']);
    $_id=trim($_GET['_id']);
    
}


//check get
if( isset($_POST['session_id']) &&  !empty($_POST['session_id'])  &&
    isset($_POST['organization_name']) &&  !empty($_POST['organization_name'])  &&
    isset($_POST['description']) &&  !empty($_POST['description'])   &&
    isset($_POST['limited_number']) &&  !empty($_POST['limited_number'])  &&  
    isset($_POST['kra_pin']) &&  !empty($_POST['kra_pin'])   &&
    isset($_POST['county_id']) &&  !empty($_POST['county_id']) &&
    isset($_POST['email_address']) &&  !empty($_POST['email_address']) &&
    isset($_POST['phone_number']) &&  !empty($_POST['phone_number']) &&
    isset($_POST['_id']) &&  !empty($_POST['_id']) 
       
        )
{
    $session_id=trim($_POST['session_id']);
    $organization_name=trim($_POST['organization_name']);
    $description=trim($_POST['description']);
    $limited_number=trim($_POST['limited_number']);
    $kra_pin=trim($_POST['kra_pin']);
    $county_id=trim($_POST['county_id']);
    $email_address=trim($_POST['email_address']);
    $phone_number=trim($_POST['phone_number']);
    $_id=trim($_POST['_id']);
    
}



if(isset($session_id) && !empty($session_id)  &&
    isset($organization_name) && !empty($organization_name)  &&
    isset($description) && !empty($description) &&
    isset($limited_number) && !empty($limited_number) &&
    isset($kra_pin) && !empty($kra_pin) &&
    isset($county_id) && !empty($county_id) &&
    isset($email_address) && !empty($email_address) &&
    isset($phone_number) && !empty($phone_number) &&
    isset($_id) && !empty($_id) 
    )
{
    
     //check if session is set
    if( isset($_SESSION[$session_id]) &&  !empty($_SESSION[$session_id]) )
    {
        $read_session=$_SESSION[$session_id];
        
             
        
        $check_exists_name= check_that_organization_non_repeats_repeat_for_self_or_not(organization_info_table_name, 'organization_name', $organization_name, $_id);
        $check_exists_kra_pin= check_that_organization_non_repeats_repeat_for_self_or_not(organization_info_table_name, 'kra_pin', $kra_pin, $_id);
        $check_exists_limited= check_that_organization_non_repeats_repeat_for_self_or_not(organization_info_table_name, 'limited_number', $limited_number, $_id);
        $check_exists_email= check_that_organization_non_repeats_repeat_for_self_or_not(organization_info_table_name, 'email_address', $email_address, $_id);
        
        if($check_exists_name==true &&
            $check_exists_kra_pin==true &&
            $check_exists_limited==true &&
            $check_exists_email==true 
                )//no match
        {
            
                if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) 
                {
                     $check_exists_county_id= CheckIfExistsTwoColumnsFunction(county_info_table_name, '_id', '_id', $county_id, $county_id);
            
                        if($check_exists_county_id==false)// match
                        {
                            
                            
                                    $update_name= UpdateTableOneCondition(organization_info_table_name, 'organization_name', $organization_name, '_id', $_id);
                                    $update_description= UpdateTableOneCondition(organization_info_table_name, 'description', $description, '_id', $_id);
                                    $update_limited_number= UpdateTableOneCondition(organization_info_table_name, 'limited_number', $limited_number, '_id', $_id);
                                    $update_kra_pin= UpdateTableOneCondition(organization_info_table_name, 'kra_pin', $kra_pin, '_id', $_id);
                                    $update_county_id= UpdateTableOneCondition(organization_info_table_name, 'county_id', $county_id, '_id', $_id);
                                    $update_email_address= UpdateTableOneCondition(organization_info_table_name, 'email_address', $email_address, '_id', $_id);
                                    $update_phone_number= UpdateTableOneCondition(organization_info_table_name, 'phone_number', $phone_number, '_id', $_id);
                                      
                                    if($update_name==true &&
                                        $update_description==true &&
                                        $update_limited_number==true &&
                                        $update_kra_pin==true &&
                                        $update_county_id==true &&
                                        $update_email_address==true &&
                                        $update_phone_number==true
                                        
                                            )
                                    {
                                        $response= json_encode(array("check"=>true,"message"=>"Successful."));
                                    }
                                    else
                                    {
                                         $response= json_encode(array("check"=>false,"message"=>"Could not update Organization at this time."));
                                    }
                            
                            

                        }
                        else
                        {
                            $response= json_encode(array("check"=>false,"message"=>"County does not exist."));
                        }
                }
                else
                {
                    $response= json_encode(array("check"=>false,"message"=>"Invalid email address."));
                }
    
           
        
        }
        else
        {
            $response= json_encode(array("check"=>false,"message"=>"Organization name or KRA pin or Limited or Email address is already in use."));
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
