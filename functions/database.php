<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//connect to db
function ConnetToDatabaseFuntion()//requires host name, user name ,pass word and database
{
        //die("Could not connect to database".'--'.database_host.'--'.database_user_name.'--'.database_user_password.'--'.database_name."\n");	// kill script incase of error
            if (!mysqli_connect(database_host,database_user_name,database_user_password,database_name))
            {
                die("Could not connect to database".'--'.database_host.'--'.database_user_name.'--'.database_user_password.'--'.database_name."\n");	// kill script incase of error
            }
            else
            {
                return mysqli_connect(database_host,database_user_name,database_user_password,database_name);
            }

}		         

