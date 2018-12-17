<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$SN =                   $_GET['term'];
include('connection.php');
$sql =                  $connection->query("SELECT ID, SN FROM consultors WHERE SN LIKE '%$SN%'");  
$output = array();

 while($row = $sql->fetch_array())
 {
    $output[] = $row['SN'];
 }
 echo json_encode($output);

