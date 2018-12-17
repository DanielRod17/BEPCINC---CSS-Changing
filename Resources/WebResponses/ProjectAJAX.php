<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('connection.php');
session_start();

if(isset($_POST['newProject'])){
    $arreglo =          $_POST['informacion'];
    $name =             $arreglo[0];
    $sponsor =          $arreglo[1];
    $leader =           $arreglo[2];
    $querty =           $connection->prepare("SELECT ID FROM project WHERE Name = ?");
    $querty ->          bind_param("s", $name);
    $querty ->          execute();
    $querty ->          store_result();
    if($querty -> num_rows == 0){
        $quertySponsor =    $connection->prepare("SELECT ID FROM sponsor WHERE Name = ?");
        $quertySponsor ->   bind_param("s", $sponsor);
        $quertySponsor ->   execute();
        $quertySponsor ->   store_result();
        if($quertySponsor -> num_rows > 0){
            $quertySponsor ->       bind_result($IDSp);
            $quertySponsor ->       fetch();
            $queryID =          $connection->query("SELECT ID FROM project ORDER BY ID DESC Limit 1");
            $queryID =          $queryID->fetch_object();
            if($queryID === null){
                $ID =               1;
            }else{
                $ID =               $queryID->ID;
                $ID =               $ID+1;
            }
            $queryMgr =         $connection->query("SELECT ManagerID FROM sponsor WHERE ID='$IDSp'");
            $queryMgrR =        $queryMgr->fetch_object();
            $mgrID =            $queryMgrR->ManagerID;
            $insertar =         $connection->prepare("INSERT INTO project (ID, Name, SponsorID, PLeader, Status, StartDate, EndDate, ManagerID) VALUES (?, ?, ?, ?, 1, ?, ?, ?)");
            $insertar ->        bind_param('isisssi', $I, $N, $S, $P, $SD, $ED, $M);
            $I =                $ID;
            $N =                $name;
            $S =                $IDSp;
            $P =                $leader;
            $Feca =             explode("/", $arreglo[3]);
            $SD =               $Feca[2]."-".$Feca[0]."-".$Feca[1];
            $Feca =             explode("/", $arreglo[4]);
            $ED =               $Feca[2]."-".$Feca[0]."-".$Feca[1];
            $M =                $mgrID;
            $insertar ->        execute();
            $insertar ->        close();
            echo "Project Added";
        }else{
            echo "Invalid Sponsor's Name";
        }
    }else{
        echo "Project Already Registered";
    }
    $querty ->          close();
}

if(isset($_POST['searchCards'])){
    
    
    $info =                 $_POST['searchCards'];
    if($_SESSION['consultor']['Type'] == '0'){
        $query =            $connection->prepare("SELECT p.*, sponsor.Name as SName, subaccount.Name as saName
                                        FROM project p
                                        INNER JOIN sponsor ON( p.SponsorID = sponsor.ID)
                                        INNER JOIN subaccount ON (subaccount.ManagerID = sponsor.ManagerID)
                                        WHERE Status='1'
                                        AND p.Name LIKE ?
                                        AND sponsor.Name LIKE ?
                                        AND p.PLeader LIKE ?
                                        AND subaccount.Name LIKE ?");
    }else{
        $query =            $connection->prepare("SELECT p.*, sponsor.Name as SName, subaccount.Name as saName
                                        FROM project p
                                        INNER JOIN sponsor ON( p.SponsorID = sponsor.ID)
                                        INNER JOIN subaccount ON (subaccount.ManagerID = sponsor.ManagerID)
                                        WHERE Status='1'
                                        AND p.Name LIKE ?
                                        AND sponsor.Name LIKE ?
                                        AND p.PLeader LIKE ?
                                        AND subaccount.Name LIKE ?
                                        AND p.ID IN (SELECT ProjectID FROM assignment WHERE ConsultorID=".$_SESSION['consultor']['Type'].")");
    }
    $query ->               bind_param("ssss", $P, $S, $Pl, $Sa);
    $P =                    "%{$info[0]}%";
    $S =                    "%{$info[1]}%";
    $Pl =                   "%{$info[2]}%";
    $Sa =                   "%{$info[3]}%";
    $query ->               execute();
    $cadena =               "";
    $resultado =            $query->get_result();
    if ($resultado->num_rows>0) {
        while($row = $resultado->fetch_assoc()) {
            
            $cadena = $cadena."<div class='contacto'>
                        <div class='number'>".$row['ID']."</div>
                        <div class='Name projName' id='".$row['ID']."' onclick='LoadPage(\"Project.php?id=".$row['ID']."\")'; >".$row['Name']."</div>
                        <div class='Sponsor'>".$row['SName']."</div>
                        <div class='Pleader'>".$row['PLeader']."</div>
                        <div class='Company'>".$row['saName']."</div>
                    </div>";
        }
        echo $cadena;
        $query -> close();
    }else{
        echo "No Results Found :(";
    }
}