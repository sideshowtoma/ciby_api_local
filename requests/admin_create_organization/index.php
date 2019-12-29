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
$organization_type=null;
$description= null;
$limited_number= null;
$kra_pin= null;
$county_id= null;
$email_address= null;
$phone_number= null;


//check post
if( isset($_GET['session_id']) &&  !empty($_GET['session_id'])  &&
    isset($_GET['organization_name']) &&  !empty($_GET['organization_name'])  &&
    isset($_GET['organization_type']) &&  !empty($_GET['organization_type'])  &&  
    isset($_GET['description']) &&  !empty($_GET['description'])   &&
    isset($_GET['limited_number']) &&  !empty($_GET['limited_number'])  &&  
    isset($_GET['kra_pin']) &&  !empty($_GET['kra_pin'])   &&
    isset($_GET['county_id']) &&  !empty($_GET['county_id']) &&
    isset($_GET['email_address']) &&  !empty($_GET['email_address']) &&
    isset($_GET['phone_number']) &&  !empty($_GET['phone_number']) 
       
        )
{
    $session_id=trim($_GET['session_id']);
    $organization_name=trim($_GET['organization_name']);
    $organization_type=trim($_GET['organization_type']);
    $description=trim($_GET['description']);
    $limited_number=trim($_GET['limited_number']);
    $kra_pin=trim($_GET['kra_pin']);
    $county_id=trim($_GET['county_id']);
    $email_address=trim($_GET['email_address']);
    $phone_number=trim($_GET['phone_number']);
    
}


//check get
if( isset($_POST['session_id']) &&  !empty($_POST['session_id'])  &&
    isset($_POST['organization_name']) &&  !empty($_POST['organization_name'])  &&
    isset($_POST['organization_type']) &&  !empty($_POST['organization_type'])  &&  
    isset($_POST['description']) &&  !empty($_POST['description'])   &&
    isset($_POST['limited_number']) &&  !empty($_POST['limited_number'])  &&  
    isset($_POST['kra_pin']) &&  !empty($_POST['kra_pin'])   &&
    isset($_POST['county_id']) &&  !empty($_POST['county_id']) &&
    isset($_POST['email_address']) &&  !empty($_POST['email_address']) &&
    isset($_POST['phone_number']) &&  !empty($_POST['phone_number']) 
       
        )
{
    $session_id=trim($_POST['session_id']);
    $organization_name=trim($_POST['organization_name']);
    $organization_type=trim($_POST['organization_type']);
    $description=trim($_POST['description']);
    $limited_number=trim($_POST['limited_number']);
    $kra_pin=trim($_POST['kra_pin']);
    $county_id=trim($_POST['county_id']);
    $email_address=trim($_POST['email_address']);
    $phone_number=trim($_POST['phone_number']);
    
}



if(isset($session_id) && !empty($session_id)  &&
    isset($organization_name) && !empty($organization_name)  &&
    isset($organization_type) && !empty($organization_type)  &&
    isset($description) && !empty($description) &&
    isset($limited_number) && !empty($limited_number) &&
    isset($kra_pin) && !empty($kra_pin) &&
    isset($county_id) && !empty($county_id) &&
    isset($email_address) && !empty($email_address) &&
    isset($phone_number) && !empty($phone_number) 
    )
{
    
     //check if session is set
    if( isset($_SESSION[$session_id]) &&  !empty($_SESSION[$session_id]) )
    {
        $read_session=$_SESSION[$session_id];
        
             
        $check_exists_name= CheckIfExistsTwoColumnsFunction(organization_info_table_name, 'organization_name', 'organization_name', $organization_name, $organization_name);
        $check_exists_kra_pin= CheckIfExistsTwoColumnsFunction(organization_info_table_name, 'kra_pin', 'kra_pin', $kra_pin, $kra_pin);
        $check_exists_limited= CheckIfExistsTwoColumnsFunction(organization_info_table_name, 'limited_number', 'limited_number', $limited_number, $limited_number);
        $check_exists_email= CheckIfExistsTwoColumnsFunction(organization_info_table_name, 'email_address', 'email_address', $email_address, $email_address);
        
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
                            
                            if( count(give_me_the_admin_levels()[(string)$organization_type]) >0 )
                            {
                                    //insert
                                    $insert= InsertIntoOrganizationInfoTable(organization_info_table_name, $organization_name, $organization_type, $description, $limited_number, $kra_pin, $county_id, $phone_number, $email_address, storable_datetime_function(time()));
                                            
                                    if($insert==true)
                                    {
                                        $response= json_encode(array("check"=>true,"message"=>"Successful."));
                                    }
                                    else
                                    {
                                         $response= json_encode(array("check"=>false,"message"=>"Could not create Organization at this time."));
                                    }
                            }
                            else
                            {
                                $response= json_encode(array("check"=>false,"message"=>"Invalid organization type."));
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
