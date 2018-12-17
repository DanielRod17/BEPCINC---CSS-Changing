<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include('connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer;
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

if(isset($_POST['checkNames'])){
    $_SESSION['cardsSubmit'] = null;
    if(isset($_POST['names']) && sizeof($_POST['names']) > 0){
        $nombres =          $_POST['names'];
        $flag =             0;
        $idUsuario =        $_SESSION['consultor']['ID'];
        foreach($nombres as $nombre){
            //$querty =           $connection->prepare("SELECT ID FROM assignment WHERE Name = ? AND (ID = (SELECT Assignment FROM consultors WHERE ID=?) OR PO = 0)");
            $querty =           $connection->prepare("SELECT ID FROM assignment WHERE Name = ? AND (ConsultorID=? OR PO = 0)");
            $querty ->          bind_param("si", $nome, $idi);
            $nome =             $nombre;
            $idi =              $idUsuario;
            $querty ->          execute();
            $querty ->          store_result();
            if($querty -> num_rows == 0){
                $flag =         1;
            }
            $querty ->          close();
        }
        if($flag == 0){
            echo "Alles gut";
        }else{
            echo "Check your projects' names";
        }
    }else{
        echo "Select at least one project";
    }
}

if(isset($_POST['usuarioBorrar'])){
    $_SESSION['usuarioBorrar'] =        $_POST['usuarioBorrar'];
}

if(isset($_POST['insertar'])){
    $_SESSION['cardsSubmit'] =  null;
    $cardsSubmit =              array();
    if(isset($_SESSION['fecha'])){
        //$queryComa =             $connection->query("SELECT ID FROM timecards WHERE StartingDay='".$_SESSION['fecha']."' AND ConsultorID='".$_SESSION['consultor']['ID']."' ");
        //if(isset($_SESSION['fecha']) && $_SESSION['fecha'] !== "" && $queryComa -> num_rows == 0){
            $lineas =            $_POST['lineas'];
            if($_POST['delete'] == '1'){
                //$queryDel =         $connection->query("DELETE FROM lineas WHERE ConsultorID = '".$_SESSION['consultor']['ID']."' AND TimecardID='1'");
            }
            $matrix;
            $counterLinea = 1;
            $arreglo =      array();
            $bandera =      0;
            foreach($lineas as $linea){
                if($linea[0] != "" && ($linea[1] != "" || $linea[2] != "" || $linea[3] != "" || $linea[4] != "" || $linea[5] != "" || $linea[6] != "" || $linea[7] != "")){
                    $matrix[] =     $linea;
                }else{
                    array_push($arreglo, $counterLinea);
                    $bandera = 1;
                }
                $counterLinea++;
            }
            if($bandera == 0){
                foreach($matrix as $linea){
                    $querty =           $connection->query("SELECT Dailycount FROM lineas WHERE DATE(CreatedDate) = DATE(NOW()) ORDER BY ID DESC LIMIT 1 ");
                    if($querty -> num_rows == 0){
                        $Daily =            1;
                    }else{
                        $Daily =            $querty->fetch_object();
                        $Daily =            $Daily->Dailycount;
                        $Daily =            $Daily+1;
                    }
                    $Name =             "TCH-".date("Y-m-d",time()-60*60*4)."-".$Daily;
                    $queryID =          $connection->query("SELECT ID FROM lineas ORDER BY ID DESC Limit 1");
                    $queryID =          $queryID->fetch_object();
                    if($queryID !== null){
                        $ID =               $queryID->ID;
                        $ID =               $ID+1;
                    }else{
                        $ID =               1;
                    }
                    $query =            $connection->query("SELECT ID FROM assignment WHERE Name='".$linea[0]."'");
                    $queryR =           $query->fetch_object();
                    $AsI =              $queryR->ID;
                    $Co =               $_SESSION['consultor']['ID'];
                    $insertar =         $connection->prepare("INSERT INTO lineas (ID, AssignmentID, ConsultorID, TimecardID, Mon, Tue, Wed, Thu, Fri, Sat, Sun, Submitted, StartingDay, CreatedDate, Dailycount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insertar ->        bind_param('iiisiiiiiiiissi', $I, $A, $C, $T, $Mo, $Tu, $We, $Th, $Fr, $Sa, $Su, $Submtted, $SD, $CD, $Da);
                    $I =                $ID;
                    $cardsSubmit[] =    $I;
                    $A =                $AsI;
                    $C =                $Co;
                    $T =                $Name;
                    $Da =               $Daily;
                    $Submtted =         0;
                    $Mo =               $linea[1];
                    $Tu =               $linea[2];
                    $We =               $linea[3];
                    $Th =               $linea[4];
                    $Fr =               $linea[5];
                    $Sa =               $linea[6];
                    $Su =               $linea[7];
                    $SD =               $_SESSION['fecha'];
                    $CD =               date("Y-m-d H:i:s");
                    $insertar ->        execute();
                    $insertar ->        close();
                }
                $_SESSION['cardsSubmit'] =  $cardsSubmit;
                echo "Timecard Saved! Leaving the page will delete it";
            }else{
                echo "Set at least an hour for line(s):   ";
               foreach($arreglo as $key => $a){
                   echo $arreglo[$key];
                   if(isset($arreglo[$key+1]))
                       echo ", ";
               }
            }
        /*}else{
            if($queryComa -> num_rows > 0)
            {
                echo "Week's timecard already registered";
            }
            else{
                echo "Select a Date";
            }
        }*/
    }else{
         echo "Select a Date";
    }
}

if(isset($_POST['fecha'])){
    $output =                   array();
    $_SESSION['fecha'] =        $_POST['fecha'];
    $_SESSION['fechaSearch'] =  $_POST['fecha'];
    $query =                    $connection->prepare("SELECT c.ID, Firstname, Lastname FROM consultors AS c
                                                        LEFT JOIN timecards AS t ON c.id = t.ConsultorID
                                                        WHERE DATE(t.StartingDay)=DATE(?) AND c.Type!='0'");
    $query ->                   bind_param("s", $feca);
    $feca =                     $_POST['fecha'];
    $query ->                   execute();
    $query ->                   bind_result($SN, $first, $last);
    while($query -> fetch()){
        //echo "$SN $first $last";
        $array =                    array("ID" => $SN, "First" => $first, "Last" => $last);
        array_push($output, $array);
    }
    echo json_encode($output);
}

if(isset($_POST['finishTimecard'])){
    $cadenaUpd =        "";
    $queryLines =       $connection->query("SELECT ID FROM lineas WHERE ConsultorID='".$_SESSION['consultor']['ID']."' AND Submitted='0'");
    if($queryLines -> num_rows > 0){
        if(isset($_SESSION['fecha'])){
            //$queryInsert =      $connection->query("INSERT INTO timecards (ID, Name, ConsultorID, StartingDay, CreatedDate, Dailycount) VALUES ('$ID', '$Name', '".$_SESSION['consultor']['ID']."','".$_SESSION['fecha']."', '".date("Y-m-d H:i:s",time()-60*60*4)."', '$Daily')");
            if($_SESSION['cardsSubmit'] !== null){
                $cadena =           implode(",", $_SESSION['cardsSubmit']);
                echo "Timecard Submitted!";
                unset($_SESSION['fecha']);
                $queryDel =         $connection->query("UPDATE lineas SET Submitted='1', SubmitDate = DATE(NOW()) WHERE ID IN($cadena) AND ConsultorID = '".$_SESSION['consultor']['ID']."' AND Submitted='0'");
            }else{
                echo "Save in progress timecard(s) first";
            }
        }else{
            echo "Select a Date";
        }
    }else{
        echo "No Timecard Saved to Submit Available";
    }
}

if(isset($_POST['nombreSearch'])){
    $arreglo =          array();
    $nombre =           $_POST['nombreSearch'];
    $fecha =            $_POST['fechaSearch'];
    $_SESSION['fechaSearch'] =  $fecha;
    $_SESSION['nombreSearch'] = $nombre;
    $query =                    $connection->prepare("SELECT lineas.*, assignment.Name
                                                    FROM lineas
                                                    LEFT JOIN assignment ON lineas.AssignmentID = assignment.ID
                                                    WHERE DATE(lineas.StartingDay)=DATE(?) AND lineas.ConsultorID = ?");
    $query ->                   bind_param("si", $feca, $idi);
    $feca =                     $_POST['fechaSearch'];
    $idi =                      $_POST['nombreSearch'];
    $query ->                   execute();
    $meta =                     $query->result_metadata();
    while ($field = $meta->fetch_field())
    {
        $params[] = &$row[$field->name];
    }
    call_user_func_array(array($query, 'bind_result'), $params);
    while ($query->fetch()) {
        foreach($row as $key => $val)
        {
            $c[$key] = $val;
        }
        $result[] = $c;
        array_push($arreglo, $result);
    }
    echo json_encode($result);
}


if(isset($_POST['actualizar'])){
    //$_SESSION['fechaSearch'] =  $fecha;
    //$_SESSION['nombreSearch'] = $nombre;
    $res =              array();
    if(isset($_SESSION['fechaSearch'])){
        $queryComa =             $connection->query("SELECT ID FROM lineas WHERE ID='".$_SESSION['cardIDSearch']."' AND ConsultorID='".$_SESSION['nombreSearch']."' ");
        if(isset($_SESSION['fechaSearch']) && $_SESSION['fechaSearch'] !== "" && $queryComa -> num_rows != 0){
            $queryComaR =       $queryComa->fetch_object();
            $timecardID =       $queryComaR->ID;
            $lineas =           $_POST['lineas'];
            $matrix;
            $counterLinea =     1;
            $arreglo =          array();
            $bandera =          0;
            foreach($lineas as $linea){
                if($linea[0] != "" && ($linea[1] != "" || $linea[2] != "" || $linea[3] != "" || $linea[4] != "" || $linea[5] != "" || $linea[6] != "" || $linea[7] != "")){
                    $matrix[] =     $linea;
                }else{
                    array_push($arreglo, $counterLinea);
                    $bandera = 1;
                }
                $counterLinea++;
            }
            if($bandera == 0){
                foreach($matrix as $linea){
                    $queryID =          $connection->query("SELECT ID FROM lineas ORDER BY ID DESC Limit 1");
                    $queryID =          $queryID->fetch_object();
                    if($queryID !== null){
                        $ID =               $queryID->ID;
                        $ID =               $ID+1;
                    }else{
                        $ID =               1;
                    }
                    $query =            $connection->query("SELECT ID FROM assignment WHERE Name='".$linea[0]."'");
                    $queryR =           $query->fetch_object();
                    $AsI =              $queryR->ID;
                    $Co =               $_SESSION['nombreSearch'];
                    $insertar =         $connection->prepare("UPDATE lineas SET AssignmentID=?, Mon=?, Tue=?, Wed=?, Thu=?, Fri=?, Sat=?, Sun=?, StartingDay=?, CreatedDate=? WHERE ID='".$_SESSION['cardIDSearch']."'");
                    $insertar ->        bind_param('iiiiiiiiss', $A, $Mo, $Tu, $We, $Th, $Fr, $Sa, $Su, $SD, $CD);
                    $A =                $AsI;
                    $Mo =               $linea[1];
                    $Tu =               $linea[2];
                    $We =               $linea[3];
                    $Th =               $linea[4];
                    $Fr =               $linea[5];
                    $Sa =               $linea[6];
                    $Su =               $linea[7];
                    $SD =               $_SESSION['fechaSearch'];
                    $CD =               date("Y-m-d H:i:s");
                    $insertar ->        execute();
                    $insertar ->        close();
                }
                $res[] =            "Timecard Updated!";
                $res[] =            $_SESSION['cardIDSearch'];
                echo json_encode($res);
            }else{
                $things =  "Set at least an hour for line(s):   ";
                foreach($arreglo as $key => $a){
                   $things = $things.$arreglo[$key];
                   if(isset($arreglo[$key+1]))
                       $things = $things.", ";
               }
               $res[] =             $things;
               echo json_encode($res);
            }
        }else{
            if($queryComa -> num_rows > 0)
            {
                $res[] = "No week's timecard registered";
               echo json_encode($res);
            }
            else{
                $res[] = "Select a Date";
                echo json_encode($res);
            }
        }
    }else{
        $res[] = "Select a Date";
        echo json_encode($res);
    }
}

if(isset($_POST['checkNaems'])){
    if(isset($_POST['names']) && sizeof($_POST['names']) > 0){
        $nombres =          $_POST['names'];
        $flag =             0;
        $idUsuario =        $_SESSION['nombreSearch'];
        foreach($nombres as $nombre){
            //$querty =           $connection->prepare("SELECT ID FROM assignment WHERE Name = ? AND (ID = (SELECT Assignment FROM consultors WHERE ID=?) OR PO = 0)");
            $querty =           $connection->prepare("SELECT ID FROM assignment WHERE Name = ? AND (ConsultorID=? OR PO = 0)");
            $querty ->          bind_param("si", $nome, $idi);
            $nome =             $nombre;
            $idi =              $idUsuario;
            $querty ->          execute();
            $querty ->          store_result();
            if($querty -> num_rows == 0){
                $flag =         1;
            }
            $querty ->          close();
        }
        if($flag == 0){
            echo "Alles gut";
        }else{
            echo "Check your projects' names";
        }
    }else{
        echo "Select at least one project";
    }
}

if(isset($_POST['cardSearch'])){
    $arreglo =          array();
    $output =           array();
    $fecha =            "";
    $nombreSe =         "";
    //$_SESSION['fechaSearch'] =  $fecha;
    //$_SESSION['nombreSearch'] = $nombre;
    $query =                    $connection->prepare("SELECT lineas.*, assignment.Name
                                                      FROM lineas
                                                      LEFT JOIN assignment ON lineas.AssignmentID = assignment.ID
                                                      WHERE lineas.ID = ?");
    $query ->                   bind_param("i", $idi);
    $idi =                      $_POST['cardSearch'];
    $_SESSION['cardIDSearch'] = $idi;
    $query ->                   execute();
    $meta =                     $query->result_metadata();
    while ($field = $meta->fetch_field())
    {
        $params[] = &$row[$field->name];
    }
    call_user_func_array(array($query, 'bind_result'), $params);
    while ($query->fetch()) {
        foreach($row as $key => $val)
        {
            $c[$key] = $val;
            if($key == "StartingDay" && $fecha == ""){
                $_SESSION['fechaSearch'] = $val;
            }
            if($key == "ConsultorID" && $nombreSe == ""){
                $_SESSION['nombreSearch'] = $val;
            }
        }
        if(isset($_POST['consultor'])){
            $_SESSION['nombreSearch'] =     $_SESSION['consultor']['ID'];
        }
        $result[] = $c;
        //array_push($arreglo, $result);
    }
    array_push($output, $result);
    array_push($output, $_SESSION['fechaSearch']);
    echo json_encode($output);
}

if(isset($_POST['aprobarCard'])){
    $id =               $_POST['aprobarCard'];
    $aprobar =          $connection->prepare("UPDATE lineas SET Submitted=1 WHERE ID=?");
    $aprobar ->         bind_param('i', $I);
    $I =                $id;
    $aprobar ->         execute();
    $aprobar ->         close();
    echo "Timecard Submitted!";
}

if(isset($_POST['searchCards'])){
    $info =                 $_POST['searchCards'];
    if($_SESSION['consultor']['Type'] != '0'){
        $insert =               array($_SESSION['consultor']['FirstName']." ".$_SESSION['consultor']['LastName']);
        array_splice( $info, 1, 0, $insert );
    }
    $results =              array();
    /*
     * SELECT l.ID, consultors.ID 
        FROM lineas l
        INNER JOIN consultors ON(consultors.ID = l.ConsultorID)
        WHERE CONCAT(consultors.Firstname,' ', consultors.Lastname) LIKE '%f%'
     * 
     * SELECT ID FROM lineas WHERE TimecardID LIKE 
     */
    if($info[5] != "All"){
        $Su =                   array(intval($info[5]));
    }else{
        $Su =                   array(0, 1);
    }
    $query =                $connection->prepare("SELECT l.ID, l.TimecardID, consultors.Firstname, consultors.Lastname, assignment.Name as aName, l.StartingDay, DATE_ADD(l.startingDay, INTERVAL 6 DAY) as EDay, l.Submitted, l.Mon, l.Tue, l.Wed, l.Thu, l.Fri, l.Sat, l.Sun 
                                                FROM lineas l
                                                INNER JOIN consultors ON(consultors.ID = l.ConsultorID)
                                                INNER JOIN assignment ON(l.AssignmentID = assignment.ID)
                                                WHERE CONCAT(consultors.Firstname,' ', consultors.Lastname) LIKE ?
                                                AND assignment.Name LIKE ?
                                                AND DATE(l.StartingDay) BETWEEN DATE(?) AND DATE(?)
                                                AND l.Submitted  IN (". implode(',', $Su) . ")
                                                AND TimecardID LIKE ?
                                                ORDER BY ID ASC");
    $query ->               bind_param("sssss", $R, $A, $SD, $ED, $T);
    $R =                    "%{$info[1]}%";
    $A =                    "%{$info[2]}%";
    $SD =                   $info[3];
    if($SD == "")
        $SD =                   "2010-01-01";
    $ED =                   $info[4];
    if($ED == "")
        $ED =                   "2100-01-01";
    $T =                    "%{$info[0]}%";
    $query ->               execute();
    $cadena =               "";
    //$query ->               bind_result($I, $TiID, $Fn, $Ln, $AN, $StarDay, $Eday, $Submitted, $Mon, $Tue, $Wed, $Thu, $Fri, $Sat, $Sun);
    $resultado =            $query->get_result();
    if ($resultado->num_rows>0) {
        while($row = $resultado->fetch_assoc()) {
            $hours =        0;
            $days =         0;
            $tagen =        array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
            for($j = 0 ; $j < count($tagen); $j++){
                $hours += $row[$tagen[$j]];
                if(intval($row[$tagen[$j]]) !== 0){
                    $days++;
                }
            }  
            $Sub =                  "Saved";
            if($row['Submitted'] == '1'){
                $Sub =                  "Submitted";
            }
            //$results[] =            array($row['ID'], $row['TimecardID'], $row['Firstname']." ".$row['Lastname'], $row['aName'], substr($row['StartingDay'], 0, 10), substr($row['EDay'], 0, 10), $Sub, $days, $hours);
            $cadena = $cadena."<div class='contacto'>";
                        $cadena = $cadena."<div class='timeCard' style='cursor: pointer;' onclick=\"LoadPage('Timecard.php?id=".$row['ID']."');\">".$row['TimecardID']."</div>";
                        $cadena = $cadena."<div class='resource'>".$row['Firstname']." ".$row['Lastname']."</div>";
                        $cadena = $cadena."<div class='tProj'>".$row['aName']."</div>";
                        $cadena = $cadena."<div class='startD'>".substr($row['StartingDay'], 0, 10)."</div>";
                        $cadena = $cadena."<div class='endD'>".substr($row['EDay'], 0, 10)."</div>";
                        $cadena = $cadena."<div class='status'>".$Sub."</div>";
                        $cadena = $cadena."<div class='totalDays'>".$days."</div>";
                        $cadena = $cadena."<div class='totalHours'>".$hours."</div>";
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
