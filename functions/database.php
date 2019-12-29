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



//script to check if a certain value exists using two column values
function CheckIfExistsTwoColumnsFunction($TableName,$ColumnData1,$ColumnData2,$MatchWith1,$MatchWith2)
{
       
        //connecting to database
        $Connection=ConnetToDatabaseFuntion();

         $query="SELECT `$ColumnData1` , `$ColumnData2` FROM `$TableName` WHERE `$ColumnData1` = '".mysqli_real_escape_string($Connection,$MatchWith1)."' AND `$ColumnData2` = '".mysqli_real_escape_string($Connection,$MatchWith2)."'"; 
        $myquery=mysqli_query($Connection,$query);
        $num=mysqli_num_rows($myquery);
        mysqli_free_result($myquery);
        if($num>=1)
        {
            
             $Connection->close();
             return false;// if the match is found 
        }
        else 
        {
             $Connection->close();
             return true;// if the match is not found

        }

}


//script to check if a certain value exists using two column values
function CheckIfExistsThreeColumnsFunction($TableName,$ColumnData1,$ColumnData2,$ColumnData3,$MatchWith1,$MatchWith2,$MatchWith3)
{
       
        //connecting to database
        $Connection=ConnetToDatabaseFuntion();

        $query="SELECT `$ColumnData1` , `$ColumnData2` , `$ColumnData3` FROM `$TableName` WHERE `$ColumnData1` = '".mysqli_real_escape_string($Connection,$MatchWith1)."' AND `$ColumnData2` = '".mysqli_real_escape_string($Connection,$MatchWith2)."'   AND `$ColumnData3` = '".mysqli_real_escape_string($Connection,$MatchWith3)."'  "; 
        $myquery=mysqli_query($Connection,$query);
        $num=mysqli_num_rows($myquery);
        mysqli_free_result($myquery);
        if($num>=1)
        {
            
             $Connection->close();
             return false;// if the match is found 
        }
        else 
        {
             $Connection->close();
             return true;// if the match is not found

        }

}



 //select on one condition from 
function SelectTableOnTwoConditions($TableName,$ConditionColumn1,$ConditionValue1,$ConditionColumn2,$ConditionValue2)
{
    
        //connecting to database
        $Connection=ConnetToDatabaseFuntion();

       $my_county_select=$my_county_select="SELECT * FROM `$TableName` WHERE `$ConditionColumn1`='".mysqli_real_escape_string($Connection,$ConditionValue1)."' AND `$ConditionColumn2`='".mysqli_real_escape_string($Connection,$ConditionValue2)."' ";
	
      
		$do_my_county_select=mysqli_query($Connection,$my_county_select);
               
                if($do_my_county_select)
                {
                        $selected_manage_data=mysqli_fetch_all($do_my_county_select,MYSQLI_ASSOC);
                         
                        $Connection->close();
                        mysqli_free_result($do_my_county_select);
                        return $selected_manage_data;
                }
                else 
                {
                         die("could not select on two conditions table--".$my_county_select);
                }

}

function SelectTableOnFourConditions($TableName,$ConditionColumn1,$ConditionValue1,$ConditionColumn2,$ConditionValue2,$ConditionColumn3,$ConditionValue3,$ConditionColumn4,$ConditionValue4)
{
    
        //connecting to database
        $Connection=ConnetToDatabaseFuntion();

       $my_county_select=$my_county_select="SELECT * FROM `$TableName` WHERE `$ConditionColumn1`='".mysqli_real_escape_string($Connection,$ConditionValue1)."' AND `$ConditionColumn2`='".mysqli_real_escape_string($Connection,$ConditionValue2)."'  AND `$ConditionColumn3`='".mysqli_real_escape_string($Connection,$ConditionValue3)."'  AND `$ConditionColumn4`='".mysqli_real_escape_string($Connection,$ConditionValue4)."'";
	
      
		$do_my_county_select=mysqli_query($Connection,$my_county_select);
               
                if($do_my_county_select)
                {
                        $selected_manage_data=mysqli_fetch_all($do_my_county_select,MYSQLI_ASSOC);
                        
                        $Connection->close();
                        mysqli_free_result($do_my_county_select);
                        return $selected_manage_data;
                }
                else 
                {
                         die("could not select on four conditions table");
                }

}

 //select on one condition from first half under table
function SelectTableAll($TableName)
{
    
        //connecting to database
        $Connection=ConnetToDatabaseFuntion();

       $my_county_select=$my_county_select="SELECT * FROM `$TableName` ";
								
		$do_my_county_select=mysqli_query($Connection,$my_county_select);
               
                if($do_my_county_select)
                {
                        $selected_manage_data=mysqli_fetch_all($do_my_county_select,MYSQLI_ASSOC);
                        
                        $Connection->close();
                        mysqli_free_result($do_my_county_select);
                        return $selected_manage_data;
                }
                else 
                {
                         die("could not select on all table");
                }

}

function SelectFromTableOnPreparedQuery($query)
{
    
        //connecting to database
        $Connection=ConnetToDatabaseFuntion();

         $my_county_select=$query;
								
		$do_my_county_select=mysqli_query($Connection,$my_county_select);
                
                if($do_my_county_select)
                {
                        $selected_manage_data=mysqli_fetch_all($do_my_county_select,MYSQLI_ASSOC);
                        
                        $Connection->close();
                        mysqli_free_result($do_my_county_select);
                        return $selected_manage_data;
                }
                else 
                {
                         die("could not select pre prepped query--".$query);
                }

}

function UpdateTableOneCondition($TableName,$set_column,$set_column_value,$check_column,$check_column_value)
{
        
        //connecting to database
        $Connection=ConnetToDatabaseFuntion();

       
        $update_ward_table_query="UPDATE `$TableName` SET 
                                   `$set_column`='$set_column_value'
                                   WHERE `$check_column`='$check_column_value' ";
                $do_my_ward_update=mysqli_query($Connection,$update_ward_table_query);
                if($do_my_ward_update)
                {

                        $Connection->close();
                        return true;
                }
                else 
                {
                       return false;
                    
                }

}


function UpdateAdminsTableOneCondition($TableName,$email_address,$telephone_number,$name,$check_column,$check_column_value)
{
        
        //connecting to database
        $Connection=ConnetToDatabaseFuntion();

       
        $update_ward_table_query="UPDATE `$TableName` SET 
                                   `email_address`='$email_address',`telephone_number`='$telephone_number',`name`='".mysqli_query($Connection,$name)."'
                                   WHERE `$check_column`='$check_column_value' ";
        
                                 
                $do_my_ward_update=mysqli_query($Connection,$update_ward_table_query);
                
                
                if($do_my_ward_update)
                {

                        $Connection->close();
                        return true;
                }
                else 
                {
                       return false;
                    
                }

}