<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$Name =                 $_GET['term'];
include('../connection.php');
$sql =                  $connection->query("SELECT ID, Name FROM project WHERE Name LIKE '%$Name%'");
$output = array();

 while($row = $sql->fetch_array())
 {
    $output[] = $row['Name'];
 }
 echo json_encode($output);
