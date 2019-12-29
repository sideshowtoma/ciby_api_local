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

//check post
if( isset($_GET['session_id']) &&  !empty($_GET['session_id'])  )
{
    $session_id=trim($_GET['session_id']);
}


//check get
if(  isset($_POST['session_id']) &&  !empty($_POST['session_id'])   )
{
    $session_id=trim($_POST['session_id']);
}


if(isset($session_id) && !empty($session_id)  )
{
     //check if session is set
    if( isset($_SESSION[$session_id]) &&  !empty($_SESSION[$session_id]) )
    {
        $read_session=$_SESSION[$session_id];
        $organization_info=SelectTableOnTwoConditions(organization_info_table_name,'_id',$read_session['organization_id'],'_id',$read_session['organization_id'])[0];
            
        $county_info=SelectTableOnTwoConditions(county_info_table_name,'_id',$organization_info['county_id'],'_id',$organization_info['county_id'])[0];
        
     
        $info_array=array("email_address"=>$read_session['email_address'],
                            "telephone_number"=> $read_session['telephone_number'],
                            "name"=>$read_session['name'],
                            "organization_name"=>$organization_info['organization_name'],
                            "description"=>$organization_info['description'],
                            "limited_number"=>$organization_info['limited_number'],
                            "kra_pin"=>$organization_info['kra_pin'],
                            "county_id"=>(int)$organization_info['county_id'],
                            "county_name"=>$county_info['county_name'],
                            "description"=>$county_info['description'],
                            "phone_number"=>$organization_info['phone_number'],
                            "organization_email_address"=>$organization_info['email_address']
                            
                            );
        
        $response= json_encode(array("check"=>true,"message"=> $info_array));
        
    }
    else 
    {
        $response= json_encode(array("check"=>false,"message"=>"Please provide a valid session id."));
    }
}
else
{
    $response= json_encode(array("check"=>false,"message"=>"Please provide a session id."));
    
}

echo $response;
 
/*
  json_encode(array(
     "1"=>array( "branch_id"=>"1","branch_name"=>"branch name 1","country"=>"Kenya","state_county_province"=>"state_county_province 1","location"=>"place 1","directions"=>"directions 1","co-ordinates"=>array(),"phone_contacts"=>array("0716214868","0716214868"),"email_contacts"=>array("info@clicksoft.co.ke","info@clicksoft.co.ke")),
     "2"=>array( "branch_id"=>"2","country"=>"Kenya","state_county_province"=>"state_county_province 2","location"=>"place 2","directions"=>"directions 2","co-ordinates"=>array(),"phone_contacts"=>array("0716214868","0716214868"),"email_contacts"=>array("info@clicksoft.co.ke","info@clicksoft.co.ke")),
        
 ));
  */
