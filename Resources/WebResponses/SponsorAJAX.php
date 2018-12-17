<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('connection.php');
session_start();

if(isset($_POST['newSponsor'])){
    $arreglo =          $_POST['informacion'];
    $name =             $arreglo[0];
    $querty =           $connection->prepare("SELECT ID FROM sponsor WHERE Name = ?");
    $querty ->          bind_param("s", $name);
    $querty ->          execute();
    $querty ->          store_result();
    if($querty -> num_rows == 0){
          $nombreManager =    explode("(", $arreglo[3]);
          $queryMang =        $connection->prepare("SELECT ID FROM account_manager WHERE Name=?");
          $queryMang ->       bind_param("s", $manager);
          $manager =          $nombreManager[0];
          $queryMang ->       execute();
          $queryMang ->       store_result();
          if($queryMang -> num_rows == 1){
              $queryMang ->       bind_result($managerID);
              $queryMang ->       fetch();
              $queryID =          $connection->query("SELECT ID FROM sponsor ORDER BY ID DESC Limit 1");
              $queryID =          $queryID->fetch_object();
              if($queryID === null){
                  $ID =               1;
              }else{
                  $ID =               $queryID->ID;
                  $ID =               $ID+1;
              }
              $insertar =         $connection->prepare("INSERT INTO sponsor (ID, Name, Email, Phone, ManagerID) VALUES (?, ?, ?, ?, ?)");
              $insertar ->        bind_param('isssi', $I, $N, $E, $P, $M);
              $I =                $ID;
              $N =                $name;
              $E =                $arreglo[1];
              $P =                $arreglo[2];
              $M =                $managerID;
              $insertar ->        execute();
              $insertar ->        close();
              echo "Sponsor Added";
        }else{
            echo "Type a Valid Manager's Name";
        }
    }else{
        echo "Sponsor Already Registered";
    }
    $querty ->          close();
}
