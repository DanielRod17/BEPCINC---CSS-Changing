<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('connection.php');
session_start();

if(isset($_POST['newAssignment'])){
    $arreglo =          $_POST['informacion'];
    $noem =             explode("(", $arreglo[5]);
    $noem =             explode(" ", $noem[0]);
    $fm =               $noem[0];
    $lm =               $noem[1];
    $name =             $arreglo[0]." - ".$fm." ".$lm;
    $project =          $arreglo[0];
    $po =               $arreglo[4];
    $br =               $arreglo[1];
    $pr =               $arreglo[3];
    $querty =           $connection->prepare("SELECT ID FROM assignment WHERE Name = ?");
    $querty ->          bind_param("s", $name);
    $querty ->          execute();
    $querty ->          store_result();
    if($querty -> num_rows == 0){
        $queryPro =         $connection->prepare("SELECT ID FROM project WHERE Name = ?");
        $queryPro ->        bind_param("s", $project);
        $queryPro ->        execute();
        $queryPro ->        store_result();
        if($queryPro -> num_rows > 0){
            $queryPo =         $connection->prepare("SELECT ID FROM po WHERE NoPO = ?");
            $queryPo ->        bind_param("s", $po);
            $queryPo ->        execute();
            $queryPo ->        store_result();
            if($queryPo -> num_rows > 0){
                $queryUs =         $connection->prepare("SELECT ID FROM consultors WHERE Firstname = ? AND Lastname = ?");
                $queryUs ->        bind_param("ss", $fm, $lm);
                $queryUs ->        execute();
                $queryUs ->        store_result();
                if($queryUs -> num_rows > 0){
                    $queryID =          $connection->query("SELECT ID FROM assignment ORDER BY ID DESC Limit 1");
                    $queryID =          $queryID->fetch_object();
                    if($queryID === null){
                        $ID =               1;
                    }else{
                        $ID =               $queryID->ID;
                        $ID =               $ID+1;
                    }
                    $queryUs ->         bind_result($consID);
                    $queryUs ->         fetch();
                    $queryPro ->        bind_result($Pro);
                    $queryPro ->        fetch();
                    $queryPo ->         bind_result($PuO);
                    $queryPo ->         fetch();
                    $queryUs ->         bind_result($Cons);
                    $queryUs ->         fetch();
                    $insertar =         $connection->prepare("INSERT INTO assignment (ID, Name, BR, PR, ProjectID, PO, ConsultorID, StartDate, EndDate, Status, Currency) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)");
                    $insertar ->        bind_param('isddiiissi', $I, $N, $B, $P,$PI, $PO, $CI, $SD, $ED, $Cu);
                    $I =                $ID;
                    $N =                $name;
                    $B =                $br;
                    $P =                $pr;
                    $PI =               $Pro;
                    $PO =               $PuO;
                    $Us =               $Cons;
                    $CI =               $consID;
                    $Cu =               $arreglo[2];
                    /////////////
                    $Feca =             explode("/", $arreglo[6]);
                    $SD =               $Feca[2]."-".$Feca[0]."-".$Feca[1];
                    
                    $Feca =             explode("/", $arreglo[7]);
                    $ED =               $Feca[2]."-".$Feca[0]."-".$Feca[1];
                    /////////////
                    $insertar ->        execute();
                    $insertar ->        close();
                    ///////////////////

                    echo "Assignment Added";
                }else{
                    echo "Select a Valid Username";
                }
            }else{
                echo "Select a Valid PO";
            }
        }else{
            echo "Select a Valid Project";
        }
    }else{
        echo "Assignment Already Registered";
    }
    $querty ->          close();
}

if(isset($_POST['searchCards'])){
    
    
    $info =                 $_POST['searchCards'];
    if($_SESSION['consultor']['Type'] != '0'){
        $query =            $connection->prepare("SELECT a.*, consultors.Firstname, consultors.Lastname, project.Name as pName, po.NoPO
                                                FROM assignment a
                                                INNER JOIN consultors ON(a.ConsultorID = consultors.ID)
                                                INNER JOIN project ON (a.ProjectID = project.ID)
                                                INNER JOIN po ON (a.PO = po.ID)
                                                WHERE a.ID > 4 AND a.ConsultorID='".$_SESSION['consultor']['ID']."'
                                                AND a.Name LIKE ?
                                                AND project.Name LIKE ?
                                                AND CONCAT(consultors.Firstname,' ', consultors.Lastname) LIKE ?
                                                AND po.NoPO LIKE ?");
    }
    else{
        $query =            $connection->prepare("SELECT a.*, consultors.Firstname, consultors.Lastname, project.Name as pName, po.NoPO
                                                FROM assignment a
                                                INNER JOIN consultors ON(a.ConsultorID = consultors.ID)
                                                INNER JOIN project ON (a.ProjectID = project.ID)
                                                INNER JOIN po ON (a.PO = po.ID)
                                                WHERE a.ID > 4
                                                AND a.Name LIKE ?
                                                AND project.Name LIKE ?
                                                AND CONCAT(consultors.Firstname,' ', consultors.Lastname) LIKE ?
                                                AND po.NoPO LIKE ?");
    }
    $query ->               bind_param("ssss", $An, $Pn, $Cn, $Pon);
    $An =                   "%{$info[0]}%";
    $Pn =                   "%{$info[1]}%";
    $Cn =                   "%{$info[2]}%";
    $Pon =                  "%{$info[3]}%";
    $query ->               execute();
    $cadena =               "";
    $resultado =            $query->get_result();
    if ($resultado->num_rows>0) {
        while($row = $resultado->fetch_assoc()) {
            
            $cadena = $cadena."<div class='contacto'>
                <div class='aName' style='cursor: pointer;' onclick=\"LoadPage('Administrators/Assignment.php?id=".$row['ID']."');\">".$row['Name']."</div>
                <div class='aProj'>".$row['pName']."</div>
                <div class='aCons'>".$row['Firstname']." ".$row['Lastname']."</div>
                <div class='aBR'>".$row['BR']."</div>
                <div class='aPR'>".$row['PR']."</div>
                <div class='aPO'>".$row['NoPO']."</div>
            </div>";
        }
        echo $cadena;
        $query -> close();
    }else{
        echo "No Results Found :(";
    }
}
