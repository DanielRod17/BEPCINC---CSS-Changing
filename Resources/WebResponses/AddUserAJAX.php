<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('connection.php');
session_start();

if (isset($_POST['informacion'])  && !isset($_POST['updateInfo'])){
    $arreglo =              $_POST['informacion'];
    ///////////////////
    if($arreglo[1] === $arreglo[2]){
        if(strlen($arreglo[1]) >= 5 ){
            if(is_numeric($arreglo[21])){
                //$querty =           $connection->query("SELECT ID FROM consultors WHERE SN = '".$arreglo[0]."'");
                $querty =           $connection->prepare("SELECT ID FROM consultors WHERE Email = ?");
                $querty ->          bind_param("s", $email);
                $email =            $arreglo[0];
                $querty ->          execute();
                $querty ->          store_result();
                if($querty -> num_rows == 0){
                    try{
                        $queryID =          $connection->query("SELECT ID FROM consultors ORDER BY ID DESC Limit 1");
                        $queryID =          $queryID->fetch_object();
                        $ID =               $queryID->ID;
                        $ID =               $ID+1;
                        $insertar =         $connection->prepare("INSERT INTO consultors (ID, Firstname, Lastname, Email, Phone, EmergencyPhone, ReportsTo, Title, Division, FunctionalArea, StartDate, EndDate, MailingAddress, MailingCity, MailingState, MailingCountry, ZipCode, NSS, RFC, Type, Schedule, Hash, Status)
                                                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $insertar ->        bind_param('isssssisissssiiisssiisi', $I, $F, $L, $E, $P, $Ep, $Rt, $T, $D, $FA, $SD, $ED, $MA, $MC, $MS, $MCo, $Z, $NSS, $RFC, $Type, $Sch, $H, $St);

                        $I =                $ID;
                        $E =                $arreglo[0];
                        $H =                sha1($arreglo[1]);
                        $F =                $arreglo[3];
                        $L =                $arreglo[4];
                        $P =                $arreglo[5]; //
                        $Ep =               $arreglo[6];
                        $Rt =               $arreglo[7];
                        //////////////////////////
                        $Feca =             $arreglo[8];
                        $Feca =             explode("/", $Feca);
                        $SD =               $Feca[2]."-".$Feca[0]."-".$Feca[1];


                        $Feca =             $arreglo[9];
                        $Feca =             explode("/", $Feca);
                        $ED =               $Feca[2]."-".$Feca[0]."-".$Feca[1];
                        //////////////////////////
                        $T =                $arreglo[10];
                        $D =                $arreglo[11];
                        $FA =               $arreglo[12];
                        $MA =               $arreglo[13];
                        $MCo =              $arreglo[14];
                        $MS =               $arreglo[15];
                        $MC =               $arreglo[16];
                        $Z =                $arreglo[17];
                        $NSS =              $arreglo[18];
                        $RFC =              $arreglo[19];
                        $Type =             $arreglo[20];
                        $Sch =              $arreglo[21];
                        $St =               1;
                        $insertar ->        execute();
                        ///////////////////
                        //var_dump($arreglo);
                        //var_dump($insertar);
                        //echo " $I $S $F $L $E  $St $R $P $Sp $As $User $insertar User Added Successfully";
                        echo "User Added Successfully";
                        $insertar ->            close();
                    }catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }else{
                    echo "Email Already Registered";
                }
            }else{
                echo "Select a Schedule";
            }
        }else{
            echo "Passwords Must Be At Least 5 Characters Long";
        }
    }else{
        echo "Passwords Must Match";
    }
}

if(isset($_POST['usuario'])){
    $usuario =              $_POST['usuario'];
    $stmt =                 $connection->prepare("SELECT ID, SN, Firstname, Lastname, Email, Roster, State, Type, Phone, Sponsor, Assignment, Status FROM consultors WHERE SN=?");
    $stmt ->                bind_param("s", $usuario);
    $stmt ->                execute();
    $stmt ->                store_result();
    if ($stmt -> num_rows != 0){
        $stmt ->                bind_result($ID, $SN, $FirstName, $LastName, $Email, $Roster, $State, $Type, $Phone, $Sponsor, $Assignment, $Status);
        $stmt ->                fetch();
        $_SESSION['EditID'] =  $ID;
        $output = array("SN" => $SN, "FirstName" => $FirstName, "LastName" => $LastName, "Email" => $Email ,"Roster" => $Roster,
            "State" => $State, "Type" => $Type, "Status" => $Status, "Phone" => $Phone, "Sponsor" => $Sponsor,
            "Assignment" => $Assignment);
        echo json_encode($output);
    }else{
        unset($_SESSION['EditID']);
        $output = array("SN" => "No User Found");
        echo json_encode($output);
    }
}

if (isset($_POST['updateInfo'])){
    if(!isset($_SESSION['EditID']) || $_SESSION['EditID'] == null  || $_SESSION['EditID'] == ''){
        echo "Select a Valid Username";
    }else{
        //echo $_SESSION['EditID'];
        $arreglo =              $_POST['informacion'];
        /*echo "Username: ".$arreglo[0]."\n";
        echo "Pass: ".$arreglo[1]."\n";
        echo "CPass: ".$arreglo[2]."\n";
        echo "First: ".$arreglo[3]."\n";
        echo "Last: ".$arreglo[4]."\n";
        echo "Country: ".$arreglo[5]."\n";
        echo "State: ".$arreglo[6]."\n";
        echo "Type: ".$arreglo[7];*/
        ///////////////////
        if(strlen($arreglo[0]) >= 5){
            if($arreglo[1] === $arreglo[2]){
                if(strlen($arreglo[1]) >= 5 ){
                    if(is_numeric($arreglo[10])){
                        $insertar =         $connection->prepare("UPDATE consultors SET SN = ?, Firstname = ?, Lastname = ?, Email =?, Roster = ?, State = ?, Type = ?, Hash = ?, Status = ?, Phone = ?, Sponsor = ?, Assignment = ? WHERE ID = ?");
                        $insertar ->        bind_param('ssssssisisiii', $S, $F, $L, $E, $R, $St, $T, $H, $Sta, $P, $Sp, $As, $I);
                        $I =                $_SESSION['EditID'];
                        $S =                $arreglo[0];
                        $F =                $arreglo[3];
                        $L =                $arreglo[4];
                        $E =                $arreglo[5]; //
                        $St =               $arreglo[7];
                        $R =                $arreglo[6];
                        $P =                $arreglo[11];
                        $Sp =               $arreglo[12];
                        $As =               $arreglo[13];
                        if($R == "MX"){
                            $St = "";
                        }
                        $T =                $arreglo[8];
                        $H =                sha1($arreglo[1]);
                        $Sta =              $arreglo[9];
                        $insertar ->        execute();
                        $insertar ->        close();
                        ///////////////////
                        $schedule =             $arreglo[10];
                        $scheduleDays =         $connection->prepare("SELECT ID, Sun, Mon, Tue, Wed, Thu, Fri, Sat FROM schedules WHERE ID=?");
                        $scheduleDays->         bind_param("s", $schedule);
                        $scheduleDays ->        execute();
                        $scheduleDays ->        store_result();
                        if ($scheduleDays -> num_rows != 0){
                            $scheduleDays ->        bind_result($SchID, $Sun, $Mon, $Tue, $Wed, $Thu, $Fri, $Sat);
                            $scheduleDays ->        fetch();
                            $Actualizar =           $connection->prepare("UPDATE consultors SET Sun = ?, Mon = ?, Tue = ?, Wed = ?,
                                                    Thu =?, Fri = ?, Sat =?, Schedule = ? WHERE ID = ?");
                            $Actualizar ->          bind_param('iiiiiiiii', $S, $M, $T, $W, $J, $V, $Sa, $Sch, $Id);
                            $Id =                   $I;
                            $S =                    $Sun;
                            $M =                    $Mon;
                            $T =                    $Tue;
                            $W =                    $Wed;
                            $J =                    $Thu;
                            $V =                    $Fri;
                            $Sa =                   $Sat;
                            $Sch =                  $SchID;
                            $Actualizar ->          execute();
                            $Actualizar ->          close();
                        }
                        unset($_SESSION['EditID']);
                        echo "User Updated Successfully";
                    }else{
                        echo "Select a Schedule";
                    }
                }else{
                    echo "Passwords Must Be At Least 5 Characters Long";
                }
            }else{
                echo "Passwords Must Match";
            }
        }else{
            echo "Username Must Include At Least 5 characters";
        }
    }
}

if(isset($_POST['getStates'])){
    $output =               array();
    $cities =               array();
    $states =               array();
    $state =                "";
    $country =              $_POST['getStates'];
    $statesSTMT =           $connection->prepare("SELECT id, name FROM states WHERE country_id=?");
    $statesSTMT ->          bind_param("s", $country);
    $statesSTMT ->          execute();
    $statesSTMT ->          bind_result($ID, $name);
    while($statesSTMT -> fetch()){
        if($state == ""){
            $state =          $ID;
        }
        $array =                    array("ID" => $ID, "Name" => $name);
        array_push($states, $array);
    }
    array_push($output, $states);
    $query =               $connection->query("SELECT id, name FROM cities WHERE state_id='$state'");
    while($row = $query->fetch_array()){
        $array =              array("ID" => $row['id'], "Name" => $row['name']);
        array_push($cities, $array);
    }
    array_push($output, $cities);
    echo json_encode($output);
}

if(isset($_POST['getCities'])){
    $cities =               array();
    $state =                $_POST['getCities'];
    $citiesSTMT =           $connection->prepare("SELECT id, name FROM cities WHERE state_id=?");
    $citiesSTMT ->          bind_param("s", $state);
    $citiesSTMT ->          execute();
    $citiesSTMT ->          bind_result($ID, $name);
    while($citiesSTMT -> fetch()){
        $array =                    array("ID" => $ID, "Name" => $name);
        array_push($cities, $array);
    }
    echo json_encode($cities, JSON_UNESCAPED_UNICODE );
}

if(isset($_POST['searchCards'])){
    $info =                 $_POST['searchCards'];
    $query =                $connection->prepare("SELECT c.ID, c.Firstname, c.Lastname, c.Phone, c.Email,  areas.Name as aName
                                                FROM consultors c
                                                INNER JOIN areas ON(c.FunctionalArea = areas.ID)
                                                WHERE CONCAT(c.Firstname,' ', c.Lastname) LIKE ?
                                                AND areas.Name LIKE ?
                                                AND c.Phone LIKE ?
                                                AND c.Email LIKE ?
                                                ORDER BY c.ID ASC");
    $query ->               bind_param("ssss", $N, $A, $P, $E);
    $N =                    "%{$info[0]}%";
    $A =                    "%{$info[3]}%";
    $P =                    "%{$info[1]}%";
    $E =                    "%{$info[2]}%";
    $query ->               execute();
    $cadena =               "";
    //$query ->               bind_result($I, $TiID, $Fn, $Ln, $AN, $StarDay, $Eday, $Submitted, $Mon, $Tue, $Wed, $Thu, $Fri, $Sat, $Sun);
    $resultado =            $query->get_result();
    if ($resultado->num_rows>0) {
        while($row = $resultado->fetch_assoc()) {
            //$results[] =            array($row['ID'], $row['TimecardID'], $row['Firstname']." ".$row['Lastname'], $row['aName'], substr($row['StartingDay'], 0, 10), substr($row['EDay'], 0, 10), $Sub, $days, $hours);
            $cadena = $cadena."<div class='contacto'>";
                        $cadena = $cadena."<div class='number'>".$row['ID']."</div>";
                        $cadena = $cadena."<div class='NameContact ctdName' id='".$row['ID']."' onclick=\"LoadPage('Administrators/Contact.php?id=".$row['ID']."');\">".$row['Firstname']." ".$row['Lastname']."</div>";
                        $cadena = $cadena."<div class='Phone'>".$row['Phone']."</div>";
                        $cadena = $cadena."<div class='Email'>".$row['Email']."</div>";
                        $cadena = $cadena."<div class='ContactOw'>".$row['aName']."</div>";
                    $cadena = $cadena."</div>";
        }
        echo $cadena;
    } else {
        echo "No Results Found :(";
        # No data actions
        //$results[] =            "No Results Found :(";
        //echo json_encode($results);
    }
}

