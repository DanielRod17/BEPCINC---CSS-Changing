<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('connection.php');
session_start();
if(isset($_POST['newSchedule'])){
    $arreglo =              $_POST['informacion'];
    if(strlen($arreglo[0]) >= 5){
        $flag =                 0;
        foreach($arreglo[3] as $day){
            if(is_numeric($day) && $day !== 0 && $day !== '0'){
                $flag = 1;
            }
        }
        if($flag == 1){
            $querty =           $connection->prepare("SELECT ID FROM schedules WHERE Name = ?");
            $querty ->          bind_param("s", $nome);
            $nome =             $arreglo[0];
            $querty ->          execute();
            $querty ->          store_result();
            if($querty -> num_rows == 0){
                $queryID =          $connection->query("SELECT ID FROM schedules ORDER BY ID DESC Limit 1");
                $queryID =          $queryID->fetch_object();
                $ID =               $queryID->ID;
                $ID =               $ID+1;
                $insertar =         $connection->prepare("INSERT INTO schedules (ID, Name, Country, State, DoubleAfter, TripleAfter) VALUES (?, ?, ?, ?, ?, ?)");
                $insertar ->        bind_param('isssii', $I, $N, $C, $St, $DA, $TA);
                $I =                $ID;
                $N =                str_replace(" ", "_", $arreglo[0]);
                $C =                $arreglo[1];
                $St =               $arreglo[2];
                if($C == "MX"){
                    $St = "";
                }
                if($arreglo[4] === "" || $arreglo[4] === null){
                    $arreglo[4] =       0;
                }
                if($arreglo[5] === "" || $arreglo[5] === null){
                    $arreglo[5] =       0;
                }
                $DA =               $arreglo[4];
                $TA =               $arreglo[5];
                $insertar ->        execute();
                $insertar ->        close();
                ///////////////////
                $i =                0;
                $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                foreach ($arreglo[3] as $day){
                    if(is_numeric($day)){
                        $Actualizar =       $connection->prepare("UPDATE schedules SET ".$dowMap[$i]." = ? WHERE ID = ?");
                        $Actualizar ->      bind_param('ii', $Ho, $Id);
                        $Id =               $ID;
                        $Ho =               $day;
                        $Actualizar ->      execute();
                        $Actualizar ->      close();
                    }
                    $i++;
                    if($i == 7){
                        break;
                    }
                }
                echo "Schedule Created Successfully";
            }else{
                echo "Schedule's Name Already Exists";
            }
        }else{
            echo "Set At Least One Day's Hour";
        }
    }else{
        echo "Name Must Be At Least 5 Characters Long";
    }
}
