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
if( isset($_GET['session_id']) &&  !empty($_GET['session_id'])  
        )
{
    $session_id=trim($_GET['session_id']);
    
}


//check get
if( isset($_POST['session_id']) &&  !empty($_POST['session_id']) 
        )
{
    $session_id=trim($_POST['session_id']);
    
}


if(isset($session_id) && !empty($session_id)  
        )
{
    
     //check if session is set
    if( isset($_SESSION[$session_id]) &&  !empty($_SESSION[$session_id]) )
    {
        
        $query="SELECT `_id`,`county_name`,`description` FROM `".county_info_table_name."` ORDER BY `".county_info_table_name."`.`county_name` ASC ";
        $counties_are=array();
        $counties= SelectFromTableOnPreparedQuery($query);
       
       $count=0;
       foreach ($counties as $value) {
        
          // echo $count++ .'::'. json_encode($value);
       }
         $response= json_encode(array("check"=>true,"message"=>$counties));

       
   
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
