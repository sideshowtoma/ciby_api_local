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
$_id=null;
$active= null;

//check post
if( isset($_GET['session_id']) &&  !empty($_GET['session_id'])  &&
    isset($_GET['_id']) &&  !empty($_GET['_id'])  &&  
    isset($_GET['active']) &&  !empty($_GET['active']) 
        )
{
    $session_id=trim($_GET['session_id']);
    $_id=trim($_GET['_id']);
    $active=trim($_GET['active']);
    
}


//check get
if( isset($_POST['session_id']) &&  !empty($_POST['session_id'])  &&
    isset($_POST['_id']) &&  !empty($_POST['_id'])  &&  
    isset($_POST['active']) &&  !empty($_POST['active']) 
        )
{
    $session_id=trim($_POST['session_id']);
    $_id=trim($_POST['_id']);
    $active=trim($_POST['active']);
    
}


if(isset($session_id) && !empty($session_id)  &&
   isset($_id) && !empty($_id)  &&
    isset($active) && !empty($active)  
        )
{
    
     //check if session is set
    if( isset($_SESSION[$session_id]) &&  !empty($_SESSION[$session_id]) )
    {
      
                $active_is=$active=='1'? 1 :2;
            
                $did_it_update=UpdateTableOneCondition(admins_info_table_name, 'active', $active_is, '_id', $_id);
                   
                             
                             if($did_it_update==true)
                             {


                                 $response= json_encode(array("check"=>true,"message"=>"Successful."));
                             }
                             else
                             {
                                 $response= json_encode(array("check"=>false,"message"=>"Could not activate or deactivate user at this time."));
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
