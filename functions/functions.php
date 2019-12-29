<?php
header('Content-Type: application/json');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function give_me_the_admin_levels()
{
    return array('1'=>array('name'=>'superadmin','organization_type'=>1),
                        '2'=>array('name'=>'mip','organization_type'=>2),
                        '3'=>array('name'=>'sp','organization_type'=>3),
                        '4'=>array('name'=>'manufacturer','organization_type'=>4)
                        );
    //return array('superadmin'=>1,'mip'=>2,'sp'=>3,'manufacturer'=>4);
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


function check_that_organization_non_repeats_repeat_for_self_or_not($table_name,$column,$value,$_id)
{
    $data_is=SelectTableOnTwoConditions($table_name,$column,$value,$column,$value);
    
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

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}


 //return datetime
function storable_datetime_function($time)
{
            date_default_timezone_set(time_zone_format);//make time kenyan
            $my_day= date('Y-m-d H:i:s',$time);
            
            return $my_day;
            
}

 //return datetime
function showable_datetime_function($time)
{
            date_default_timezone_set(time_zone_format);//make time kenyan
            $my_day= date('d/m/Y',$time);
            
            return $my_day;
            
}

function showable_day_month_function($time)
{
            date_default_timezone_set(time_zone_format);//make time kenyan
            $my_day= date('d/m',$time);
            
            return $my_day;
            
}


function single_string_date_function($time)
{
            date_default_timezone_set(time_zone_format);//make time kenyan
            $my_day= date('Hisd',$time);
            
            return $my_day;
            
}


 //return year
function storable_year_function($time)
{
            date_default_timezone_set(time_zone_format);//make time kenyan
            $my_day= date('Y',$time);
            
            return $my_day;
            
}

 //return date
function storable_time_function($time)
{
            date_default_timezone_set(time_zone_format);//make time kenyan
            $my_day= date('H:i:s',$time);
            
            return $my_day;
            
}

function UTCTimeToLocalTime($time, $tz, $FromDateFormat = 'Y-m-d H:i:s', $ToDateFormat = 'H:i:s d-m-Y')
{
      if ($tz == '') {
        $tz = date_default_timezone_get();
    }

    $utc_datetime = DateTime::createFromFormat($FromDateFormat, $time);
$local_datetime = $utc_datetime;

$local_datetime->setTimeZone(new DateTimeZone($tz));
return $local_datetime->format($ToDateFormat);
}
