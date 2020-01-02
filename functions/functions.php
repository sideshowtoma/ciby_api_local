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

function make_a_reset_key($email_address)
{
    
    return rand(100000, 999999);
    
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


function mail_sender_function($address,$name,$body,$alt_body,$subject,$attachment_1,$attachment_2)
{
        $mail             = new PHPMailer();

   
        $mail->IsSMTP(); 
        $mail->Host       = mail_host; 
        $mail->SMTPDebug  = 0;                  
        $mail->SMTPAuth   = true;                  
        $mail->Host       = mail_host;
        $mail->Port       = mail_port;                    
        $mail->Username   = mail_user_name; 
        $mail->Password   = mail_password;        

        $mail->SetFrom(mail_user_name, mail_sender);

        $mail->AddReplyTo(mail_user_name,mail_sender);

        $mail->Subject    = $subject;
        $mail->AltBody    = $alt_body; 

        $mail->MsgHTML($body);

        
        $mail->AddAddress($address, $name);

        $mail->AddAttachment($attachment_1);
        $mail->AddAttachment($attachment_2);

        if(!$mail->Send()) {
         // echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
         // echo "Message sent!";
            return true;
        }
}


function make_user_created_invitational_email_html($name,$user_name,$password,$login_url)
{
    return '<html>'
    . '<body>'
            . '<div style="background-color:#396270 !important;color:#FFFFFF">'
            . '<h4>Hello <b>'.strtoupper($name).'</b> welcome to the ciby platform.</h4>'
            . '<p>To login into the platform please use the following credentials.</p>'
            . '<table style="color:#FFFFFF">'
                . '<tr>'
                    . '<td><b>Username/Email:</b></td>'. '<td>'.$user_name.'</td>'
                . '</tr>'
                . '<tr>'
                    . '<td><b>Password:</b></td>'. '<td>'.$password.'</td>'
                . '</tr>'
                . '<tr>'
                    . '<td><b>Website link:</b></td>'. '<td><a href="'.$login_url.'">Login</a></td>'
                . '</tr>'
            . '</table>'
            . '</div>'
            . '</br>'
            . '<div style="background-color:#eaeaea !important" class="logo-img"><a href="http://ciby.co.ke/ class="custom-logo-link" rel="home" itemprop="url"><img width="225" height="106" src="http://ciby.co.ke/logo.png" class="custom-logo" alt="ciby Limited." itemprop="logo"></a></div>'
           
    . '</body>'
    . '</html>';
}



function make_user_get_password_reset_url_email_html($name,$url)
{
    return '<html>'
    . '<body>'
            . '<div style="background-color:#396270 !important;color:#FFFFFF">'
            . '<h4>Hello <b>'.strtoupper($name).'</b> below is your password rest website link.</h4>'
            . '<p>To reset your password click on the link below.</p>'
            . '<table style="color:#FFFFFF">'
                . '<tr>'
                    . '<td><b>Reset website link:</b></td>'. '<td>'.$url.'</td>'
                . '</tr>'
                . '<tr>'
                    . '<td><b>Or click here:</b></td>'. '<td><a href="'.$url.'">Here</a></td>'
                . '</tr>'
            . '</table>'
            . '</div>'
            . '</br>'
            . '<div style="background-color:#eaeaea !important" class="logo-img"><a href="http://ciby.co.ke/ class="custom-logo-link" rel="home" itemprop="url"><img width="225" height="106" src="http://ciby.co.ke/logo.png" class="custom-logo" alt="ciby Limited." itemprop="logo"></a></div>'
           
    . '</body>'
    . '</html>';
}

function make_user_get_password_recover_url_email_html($name)
{
    return '<html>'
    . '<body>'
            . '<div style="background-color:#396270 !important;color:#FFFFFF">'
            . '<h4>Hello <b>'.strtoupper($name).'</b>.</h4>'
            . '<p>Your password has been reset, if you did not perform this action please contact your admin.</p>'
            . '</div>'
            . '</br>'
            . '<div style="background-color:#eaeaea !important" class="logo-img"><a href="http://ciby.co.ke/ class="custom-logo-link" rel="home" itemprop="url"><img width="225" height="106" src="http://ciby.co.ke/logo.png" class="custom-logo" alt="ciby Limited." itemprop="logo"></a></div>'
           
    . '</body>'
    . '</html>';
}