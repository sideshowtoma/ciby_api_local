<?php
header('Content-Type: application/json');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function give_me_the_admin_levels()
{
    return array('superadmin'=>1,'mip'=>2,'sp'=>3,'manufacturer'=>4);
}

//function the session maker
function make_a_session_key($email_address)
{
    $session= rand(100000, 10000000).time().md5($email_address);
    
    return sha1($session, false);
    
}

function generateStrongPassword($length , $add_dashes , $available_sets)
{
	$sets = array();
	if(strpos($available_sets, 'l') !== false)
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
	if(strpos($available_sets, 'u') !== false)
		$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
	if(strpos($available_sets, 'd') !== false)
		$sets[] = '23456789';
	if(strpos($available_sets, 's') !== false)
		$sets[] = '!@#$%&*?';
	$all = '';
	$password = '';
	foreach($sets as $set)
	{
		$password .= $set[array_rand(str_split($set))];
		$all .= $set;
	}
	$all = str_split($all);
	for($i = 0; $i < $length - count($sets); $i++)
		$password .= $all[array_rand($all)];
	$password = str_shuffle($password);
	if(!$add_dashes)
		return $password;
	$dash_len = floor(sqrt($length));
	$dash_str = '';
	while(strlen($password) > $dash_len)
	{
		$dash_str .= substr($password, 0, $dash_len) . '-';
		$password = substr($password, $dash_len);
	}
	$dash_str .= $password;
	return $dash_str;
}


function check_that_only_the_admin_of_the_email_has_that_email_or_no_one_has_it($email_address,$_id)
{
    $data_is=SelectTableOnTwoConditions(admins_info_table_name,'email_address',$email_address,'email_address',$email_address);
    
    if(count($data_is)>0)
    {
        //make sure the count is one
        if(count($data_is)==1)
        {
            $_id_is=$data_is[0]['_id'];
            
            if($_id_is==$_id)//if user is same
            {
                 return true; 
            } else 
            {
                return false; 
            }
        }
        else
        {
           return false; 
        }
    }
    else
    {
        return true;
    }
}