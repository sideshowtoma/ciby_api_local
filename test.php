<?php
require './functions/session.php';
require './functions/constants.php';
require './functions/database.php';
require './functions/functions.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (function_exists('mysqli_connect')) {
    echo "mysqli_connect functions are available.<br />\n";
} else {
    echo "mysqli_connect functions are not available.<br />\n";
}
ConnetToDatabaseFuntion();