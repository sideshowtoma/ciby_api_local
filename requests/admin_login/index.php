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

$email_address=null;
$password=null;

//check post
if( isset($_GET['email_address']) &&  isset($_GET['password']) && !empty($_GET['email_address']) &&  !empty($_GET['password'])  )
{
    $email_address=trim($_GET['email_address']);
    $password=$_GET['password'];
}


//check get
if(  isset($_POST['email_address']) &&  isset($_POST['password']) && !empty($_POST['email_address']) &&  !empty($_POST['password'])     )
{
    $email_address=trim($_POST['email_address']);
    $password=$_POST['password'];
}


if(isset($email_address) &&  isset($password) && !empty($email_address) &&  !empty($password) )
{
    
     //check
    $check=CheckIfExistsTwoColumnsFunction(admins_info_table_name,'email_address','password',$email_address,md5($password));
    
      
    if($check==false)//exists
    {
        
        //get the items in the table and set the session
        $admin_info=SelectTableOnTwoConditions(admins_info_table_name,'email_address',$email_address,'password',md5($password))[0];
        
        
       if($admin_info['active']==1)
       {
            $organization_info=SelectTableOnTwoConditions(organization_info_table_name,'_id',$admin_info['organization_id'],'_id',$admin_info['organization_id'])[0];
            $session_id=make_a_session_key($email_address);
            $_SESSION[$session_id]=$admin_info;
            $response= json_encode(array("check"=>true,"session_id"=>$session_id,"message"=> "Success." ,"organization_type"=>(int)$organization_info['organization_type'], "set_cookie"=>"PHPSESSID=".session_id()) );
        
       }
       else
       {
            $response= json_encode(array("check"=>false,"session_id"=>false,"message"=>"User has been deactivated.","organization_type"=>null,"set_cookie"=> null ));
       }
        
       
    }
    else
    {
        $response= json_encode(array("check"=>false,"session_id"=>false,"message"=>"Wrong username/email address and password combination.","organization_type"=>null,"set_cookie"=> null ));
    }
}
else
{
    $response= json_encode(array("check"=>false,"session_id"=>false,"message"=>"Please provide email address as username and a password.","organization_type"=>null,"set_cookie"=>null));
    
}

 echo $response;