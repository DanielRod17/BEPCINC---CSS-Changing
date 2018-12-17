<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    session_start();
    session_destroy();
    session_unset();
    unset($_SESSION['consultor']);
    //$connection =           new MySQLi("localhost", "root", "@quatech14", "BoxPalletID");
    //$user =                 $_SESSION['user'];
    //$querito =              "UPDATE users SET Logged='0', SessionID='' WHERE SN='$user'";
    //$executeQuery1 =        mysqli_query($connection,$querito);
    //unset($_SESSION['loggedin']);  // where $_SESSION["nome"] is your own variable. if you do not have one use only this as follow **session_unset();**
    header("Location: index.php");
?>
