<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('connection.php');
session_start();

if(isset($_POST['newPO'])){
    $arreglo =          $_POST['informacion'];
    $number =           $arreglo[0];
    $querty =           $connection->prepare("SELECT ID FROM po WHERE NoPO = ?");
    $querty ->          bind_param("s", $number);
    $querty ->          execute();
    $querty ->          store_result();
    if($querty -> num_rows == 0){
        $queryID =          $connection->query("SELECT ID FROM po ORDER BY ID DESC Limit 1");
        $queryID =          $queryID->fetch_object();
        if($queryID === null){
            $ID =               1;
        }else{
            $ID =               $queryID->ID;
            $ID =               $ID+1;
        }
        $insertar =         $connection->prepare("INSERT INTO po (ID, NoPO, Ammount, Currency, Status) VALUES (?, ?, ?, ?, ?)");
        $insertar ->        bind_param('issii', $I, $N, $A, $C, $S);
        $I =                $ID;
        $N =                $arreglo[0];
        $A =                $arreglo[1];
        $C =                $arreglo[2];
        $S =                $arreglo[3];
        $insertar ->        execute();
        $insertar ->        close();
        echo "PO Added";
    }else{
        echo "PO Number Already Registered";
    }
    $querty ->          close();
}

if(isset($_POST['searchCards'])){
    
    
    $info =                 $_POST['searchCards'];
    
    
    if($info[2] == "All"){
        $Cu =               array(0, 1);
    }else{
        $Cu =               array(intval($info[2]));
    }
    if($info[3] == "All"){
        $Sta =              array(0, 1, 2);
    }else{
        $Sta =              array(intval($info[3]));
    }
    $query =                $connection->prepare("SELECT p.*
                                                FROM po p
                                                WHERE p.NoPO LIKE ?
                                                AND p.Currency IN (". implode(',',$Cu) . ")
                                                AND p.Status IN (". implode(',',$Sta) . ")");
    $query ->               bind_param("s", $Po);
    $Po =                   "%{$info[0]}%";
    $query ->               execute();
    $cadena =               "";
    //$query ->               bind_result($I, $TiID, $Fn, $Ln, $AN, $StarDay, $Eday, $Submitted, $Mon, $Tue, $Wed, $Thu, $Fri, $Sat, $Sun);
    $resultado =            $query->get_result();
    if ($resultado->num_rows>0) {
        while($row = $resultado->fetch_assoc()) {
            $querty =           $connection->prepare("SELECT project.Name as pName
                                                    FROM assignment a
                                                    INNER JOIN po ON (a.PO = po.ID)
                                                    INNER JOIN project ON (a.ProjectID = project.ID)
                                                    WHERE po.ID=".$row['ID']."
                                                    AND project.Name LIKE ?");
            $querty ->          bind_param("s", $projeto);
            $projeto =          "%{$info[1]}%";
            $querty ->          execute();
            $querty ->          store_result();
            if($querty->num_rows > 0){
                $querty ->          bind_result($pName);
            }else{
                $pName =            "No Project Assigned";
            }
            if($row['Currency'] == '0')
                $currency =       "MXN";
            else
                $currency =       "USD";

            if($row['Status'] == '0')
                $status =         "Inactive";
            else if($row['Status'] == '1')
                $status =         "Active";
            else
                $status =         "Temporal";
            $cadena = $cadena."<div class='contacto'>";
                    $cadena = $cadena."<div class='number'>".$row['ID']."</div>";
                    $cadena = $cadena."<div class='poNumber' style='cursor: pointer;' onclick=\"LoadPage('Administrators/PO.php?id=".$row['ID']."');\" >".$row['NoPO']."</div>";
                    $cadena = $cadena."<div class='poProject'>".$pName."</div>";
                    $cadena = $cadena."<div class='poAmmount'>$".$row['Ammount']."</div>";
                    $cadena = $cadena."<div class='poCurrency'>".$currency."</div>";
                    $cadena = $cadena."<div class='poStatus'>$status</div>";
                $cadena = $cadena."</div>";
        }
        echo $cadena;
        $query -> close();
    }else{
        echo "No Results Found :(";
    }
}