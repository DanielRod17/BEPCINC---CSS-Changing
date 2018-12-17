<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
$Name =                 $_GET['term'];
include('../connection.php');

$user =                 $_SESSION['consultor']["ID"];
$sql =                  $connection->query("SELECT Name FROM assignment WHERE Name LIKE '%$Name%' AND (consultorID='2' OR PO='0')");
$output = array();

 while($row = $sql->fetch_array())
 {
    $output[] = $row['Name'];
 }
 echo json_encode($output);
