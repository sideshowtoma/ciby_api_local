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
$organization_type= null;
$sort_by= null;
$sort_order= null;
$skip=null;
$limit=null;

//check post
if( isset($_GET['session_id']) &&  !empty($_GET['session_id'])  &&  
    isset($_GET['organization_type']) &&  !empty($_GET['organization_type'])  &&  
    isset($_GET['sort_by']) &&  !empty($_GET['sort_by'])  &&  
    isset($_GET['sort_order']) &&  !empty($_GET['sort_order'])  &&  
    isset($_GET['limit']) &&  !empty($_GET['limit'])   
        )
{
    $session_id=trim($_GET['session_id']);
    $organization_type=trim($_GET['organization_type']);
    $sort_by=trim($_GET['sort_by']);
    $sort_order=trim($_GET['sort_order']);
    $limit=trim($_GET['limit']);
    $skip=trim($_GET['skip']);
     
}


//check get
if( isset($_POST['session_id']) &&  !empty($_POST['session_id'])  &&  
    isset($_POST['organization_type']) &&  !empty($_POST['organization_type'])  &&  
    isset($_POST['sort_by']) &&  !empty($_POST['sort_by'])  &&  
    isset($_POST['sort_order']) &&  !empty($_POST['sort_order'])  &&  
    isset($_POST['limit']) &&  !empty($_POST['limit'])   
        )
{
    $session_id=trim($_POST['session_id']);
    $organization_type=trim($_POST['organization_type']);
    $sort_by=trim($_POST['sort_by']);
    $sort_order=trim($_POST['sort_order']);
    $limit=trim($_POST['limit']);
    $skip=trim($_POST['skip']);
     
}


if(isset($session_id) && !empty($session_id) &&
    isset($organization_type) && !empty($organization_type) &&
    isset($sort_by) && !empty($sort_by) &&
        isset($sort_order) && !empty($sort_order) &&
   isset($limit) && !empty($limit) 
        )
{
     //check if session is set
    if( isset($_SESSION[$session_id]) &&  !empty($_SESSION[$session_id]) )
    {
        
        if($limit>0 && is_numeric($limit) && ($skip=='' || ($skip>=0 && is_numeric($skip))  ) )
        {
            $sort_order= strtoupper($sort_order);
            
            if($sort_order=='ASC' || $sort_order=='DESC')
            {
                   $query_all="SELECT * FROM `".organization_info_table_name."` WHERE `organization_type`= '".$organization_type."' ORDER BY `".organization_info_table_name."`.`".$sort_by."` $sort_order LIMIT ".$limit." OFFSET ".$skip." ";
           
                    $info_all= SelectFromTableOnPreparedQuery($query_all);

                    $array=array();

                    foreach ($info_all as $value) 
                    {
                        $query_all="SELECT * FROM `".organization_info_table_name."` WHERE `organization_type`= '".$organization_type."' ORDER BY `".organization_info_table_name."`.`".$sort_by."` $sort_order LIMIT ".$limit." OFFSET ".$skip." ";
                        $info_all= SelectFromTableOnPreparedQuery($query_all);
                        
                        $query_all_county="SELECT `_id`,`county_name`,`description` FROM `".county_info_table_name."` WHERE `_id`= '".$value['county_id']."'  ";
                        
                        
                        
                            $array[count($array)]=array(
                                '_id'=>  $value['_id'],
                                'organization_name'=>  $value['organization_name'],
                                'organization_type'=>  give_me_the_admin_levels()[$organization_type],
                                'description'=>  $value['description'],
                                'limited_number'=>  $value['limited_number'],
                                'kra_pin'=>  $value['kra_pin'],
                                'county_id'=>   SelectFromTableOnPreparedQuery($query_all_county)[0],
                                'phone_number'=>  $value['phone_number'],
                                'email_address'=>  $value['email_address'],
                                'time_stamp'=>  UTCTimeToLocalTime($value['time_stamp'],''),
                            );

                    }
                    
                     $response= json_encode(array("check"=>true,"message"=>$array));
           
            }
            else
            {
                 $response= json_encode(array("check"=>false,"message"=>"Sort order can only be DESC for descending or ASC for ascending order."));
            }
            
            
           
           
        }
        else
        {
            $response= json_encode(array("check"=>false,"message"=>"Invalid skip or limit values, skip must be a number equal to 0 or greater than 0 and limit must be a number greater than 0."));
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
