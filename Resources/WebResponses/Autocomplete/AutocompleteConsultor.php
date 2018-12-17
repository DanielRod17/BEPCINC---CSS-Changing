<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$Name =                 $_GET['term'];
include('../connection.php');
$sql =                  $connection->query("SELECT Firstname, Lastname, Email FROM consultors WHERE (SN LIKE '%$Name%' OR Firstname LIKE '%$Name%' OR Email LIKE '%$Name%') AND ID!=0 AND Type!=0");
$output = array();
/***/
 while($row = $sql->fetch_array())
 {
    $cadena =   $row['Firstname']." ".$row['Lastname']."(".$row['Email'].")";
    $output[] = $cadena;
 }
 echo json_encode($output);
