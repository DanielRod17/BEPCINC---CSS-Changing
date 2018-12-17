<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$Name =                 $_GET['term'];
include('../connection.php');
$sql =                  $connection->query("SELECT a.Name as Name, subaccount.Name as Acc
                                            FROM account_manager a
                                            INNER JOIN subaccount ON(a.SubAccountID = subaccount.ID)
                                            WHERE a.Name LIKE '%$Name%'");
$output = array();

 while($row = $sql->fetch_array())
 {
    $cadena =     $row['Name']."(".$row['Acc'].")";
    $output[] =   $cadena;
 }
 echo json_encode($output);
