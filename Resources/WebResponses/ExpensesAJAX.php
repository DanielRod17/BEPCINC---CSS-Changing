<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include('connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer;
session_start();

if(isset($_POST['searchConsultor'])){
    $output =             array();
    $stmt =               $connection->prepare("SELECT Name, ID
                                                FROM assignment
                                                WHERE ConsultorID = (SELECT ID
                                                                    FROM consultors
                                                                    WHERE Email = ?)");
    $stmt->               bind_param("s", $correo);
    $correo =             $_POST['searchConsultor'];
    $stmt ->              execute();
    $stmt ->              bind_result($Proj, $ID);
    while($stmt -> fetch()){
        $array =                    array("ID" => $ID, "Proj" => $Proj);
        array_push($output, $array);
    }
    echo json_encode($output);
}

if(isset($_POST['asignarExpense'])){
    $arreglo =          $_POST['asignarExpense'];
    if(!empty($arreglo) && $arreglo[0] !== "" && $arreglo[1] !== "" && $arreglo[2] !== "" && $arreglo[3] !== ""){
        $getConsultor =     $connection->prepare("SELECT consultors.ID, consultors.Firstname, consultors.Lastname, project.Name 
                                                    FROM assignment a
                                                    INNER JOIN consultors ON(a.ConsultorID = consultors.ID)
                                                    INNER JOIN project ON(project.ID = a.ProjectID)
                                                    WHERE a.ID=?");
        $getConsultor ->    bind_param("i", $ai);
        $ai =               $arreglo[4];
        $getConsultor ->    execute();
        $getConsultor ->    bind_result($CiD, $Cna, $Cla, $Pna);
        $getConsultor ->    fetch();
        $getConsultor ->    close();
        $stmt =             $connection->prepare("INSERT INTO travels (ID, Name, AssignmentID, Status, FromDate, ToDate, ConsultorID) VALUES (NULL, ?, ?, 1, ?, ?, ?)");
        $stmt ->            bind_param("sissi", $N, $A, $SD, $ED, $C);
        $N =                $arreglo[1];
        $A =                $arreglo[4];
        $C =                $CiD;
        $SD =               $arreglo[2];
        $ED =               $arreglo[3];
        $stmt ->            execute();
        $fechaIni = substr($arreglo[2], 0, 10);
        $fechaFin = substr($arreglo[3], 0, 10);
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Host = "smtp.office365.com"; // Your SMTP PArameter
        $mail->Port = 587; // Your Outgoing Port
        $mail->SMTPAuth = true; // This Must Be True
        $mail->Username = "betracking@bepcinc.com"; // Your Email Address
        $mail->Password = "Bepcinc1"; // Your Password
        $mail->SMTPSecure = 'tls'; // Check Your Server's Connections for TLS or SSL

        $mail->From = "betracking@bepcinc.com";
        $mail->FromName = "Be Tracking";
        $mail->AddAddress($arreglo[0]);
        $mail->AddEmbeddedImage('../bee-logo1.png', 'logo1');
        $mail->AddEmbeddedImage('../bee logo.png', 'logo0');

        $mail->IsHTML(true);

        $mail->Subject = "Expense Request";
        $mail->Body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

                    <html xmlns='http://www.w3.org/1999/xhtml'>
                    <head>
                    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
                    <title>Demystifying Email Design</title>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
                    </head>
                        <body style='margin: 0; padding: 0;'>
                            <table align='center' border='1' cellpadding='0' cellspacing='0' width='600' style='border-collapse: collapse;'>
                                <tr>
                                    <td bgcolor='#254366' height='100px' style='color: white; text-align: center; font-size: 50px;'>
                                        You were assigned a travel
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor='#ffffff'>
                                        <table cellpadding='20' cellspacing='0' width='600' style='border-collapse: collapse;'> 
                                            <tr>
                                                <td height='80px' colspan='2'>
                                                    <img src='cid:logo1' alt='' width='420' height='60' style='margin-left: 10%; display: block;'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style='height: 130px; width: 100px;'>
                                                    <img src='cid:logo0' width='50' height='78'></img>
                                                </td>
                                                <td>
                                                    Dear $Cna $Cla:<br>
                                                    A travel expense's report has been opened for you to work in the project<br>
                                                    $Pna. Effective from $fechaIni to $fechaFin<br>
                                                    From now on, you can register your expenses in the portal
                                                    www.betracking.com
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor='#ee4c50'>
                                     Row 3
                                    </td>
                                </tr>
                            </table>
                        </body>
                    </html>
                       ";

            if(!$mail->Send())
            {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
            else
            {
                echo 'Email Sent';
            }
    }else{
        echo "Fill the form";
    }
}

if(isset($_FILES) && isset($_POST['Travel'])){
    //echo $_POST['Travel'];
    $totalArr = (count($_POST)-1)/7;
    if($totalArr > 0){
        $date =             date("Y-m-d");
        $cadenita =         "";
        $errors =           array();
        $files =            array();
        $expensions =       array("jpeg","jpg","png", "pdf");
        $fiNam =            '1';
        for($i = 1; $i <= $totalArr; $i++){
            if($_POST["Travel$i-1"] == "" || $_POST["Travel$i-1"] == null){
                $errors[] =         "Select a valid date in line $i<br>";
            }
            if($_POST["Travel$i-3"] == "" || $_POST["Travel$i-3"] == null){
                $errors[] =         "Set amount in line $i<br>";
            }
        }
        if(empty($errors)){
            $flag =         0;
            foreach($_FILES as $file){
                $nombre =       $file['name'];
                $tmp =          $file['tmp_name'];
                $ext =          explode('.', $nombre);
                $ext =          strtolower(end($ext));
                if(in_array($ext, $expensions )=== false){
                    $flag =         1;
                    $errors[] =     "Couldn't upload file(s):  $nombre<br>";
                }else{
                    $dirname =      $_SESSION['consultor']['Email'];
                    $cadenita =     $cadenita."$date/".$nombre."~";
                    $files[] =      "$dirname/$date";
                }
            }
            if($flag == 0){
                foreach($files as $nambre){
                    if (!is_dir("../../Files/Expenses/$nambre")) {
                        mkdir("../../Files/Expenses/$nambre", 0775, true);
                        move_uploaded_file($tmp, "../../Files/Expenses/$nambre/".$nombre);
                    }else{
                        move_uploaded_file($tmp, "../../Files/Expenses/$nambre/".$nombre);
                    }
                }
                for($i = 1; $i <= $totalArr; $i++){
                    //echo $_POST["Travel$i-0"]." - ".$_POST["Travel$i-1"]." - ".$_POST["Travel$i-2"]." - ".$_POST["Travel$i-3"]." - ".$_POST["Travel$i-4"]." - ".$_POST["Travel$i-5"]." - ".$_POST["Travel$i-6"]."\n<br>";
                    $insertar =         $connection->prepare("INSERT INTO expenses (ID, TravelID, Category, Name, SubmitDate, ExpenseDate, Quantity, Currency, Billable, Refundable, Attachments, Status) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
                    $insertar ->        bind_param("iisssdiiis", $T, $Ca, $N, $SD, $ED, $Q, $C, $B, $R, $A);
                    $T =                $_POST['Travel'];
                    $Ca =               $_POST["Travel$i-0"];
                    $N =                $_POST["Travel$i-2"];
                    $SD =               $date;
                    //$dateE =            explode("/", $_POST["Travel$i-1"]);
                    //$dateE =            $dateE[2]."-".$dateE[0]."-".$dateE[1];
                    $ED =               $_POST["Travel$i-1"];
                    $Q =                $_POST["Travel$i-3"];
                    $C =                $_POST["Travel$i-4"];
                    $B =                $_POST["Travel$i-5"];
                    if($B == 'true')
                        $B =                1;
                    else
                        $B =                0;
                    $R =                $_POST["Travel$i-6"];
                    if($R == 'true')
                        $R =                1;
                    else
                        $R =                0;
                    $A =                $cadenita;
                    $rc =               $insertar ->        execute();
                    /*if ( false===$rc ) {
                        die('execute() failed: ' . htmlspecialchars($insertar->error));
                    }else{

                    }
                    echo "Added";*/
                }
                echo "Expenses Saved";
                $username =     $_SESSION['consultor']['FirstName']. " " . $_SESSION['consultor']['LastName']."<br>".$_SESSION['consultor']['Email'];
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->Host = "smtp.office365.com"; // Your SMTP PArameter
                $mail->Port = 587; // Your Outgoing Port
                $mail->SMTPAuth = true; // This Must Be True
                $mail->Username = "betracking@bepcinc.com"; // Your Email Address
                $mail->Password = "Bepcinc1"; // Your Password
                $mail->SMTPSecure = 'tls'; // Check Your Server's Connections for TLS or SSL

                $mail->From = "betracking@bepcinc.com";
                $mail->FromName = "Be Tracking";
                $mail->AddAddress("daniel.rod.vega@hotmail.com");
                $mail->AddEmbeddedImage('../bee-logo1.png', 'logo1');
                $mail->AddEmbeddedImage('../bee logo.png', 'logo0');

                $mail->IsHTML(true);

                $mail->Subject = "Expense Request";
                $mail->Body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>

                            <html xmlns='http://www.w3.org/1999/xhtml'>

                             <head>

                            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

                            <title>Demystifying Email Design</title>

                            <meta name='viewport' content='width=device-width, initial-scale=1.0'/>

                            </head>
                                <body style='margin: 0; padding: 0;'>

                                    <table align='center' border='1' cellpadding='0' cellspacing='0' width='600' style='border-collapse: collapse;'>
                                        <tr>
                                            <td bgcolor='#254366' height='100px' style='color: white; text-align: center; font-size: 50px;'>
                                                Expense Submitted
                                            </td>
                                        </tr>
                                        <tr>
                                            <td bgcolor='#ffffff'>

                                                <table cellpadding='20' cellspacing='0' width='600' style='border-collapse: collapse;'> 
                                                    <tr>
                                                        <td height='80px' colspan='2'>
                                                            <img src='cid:logo1' alt='' width='420' height='60' style='margin-left: 10%; display: block;'/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style='height: 130px; width: 100px;'>
                                                            <img src='cid:logo0' width='50' height='78'></img>
                                                        </td>
                                                        <td>
                                                            $username<br>
                                                            Has submitted $totalArr new expenses. Login into Betracking to check them out!
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                        </td>
                                                        <td>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td bgcolor='#ee4c50'>
                                             Row 3
                                            </td>
                                        </tr>
                                    </table>
                                </body>
                            </html>
                               ";

                if(!$mail->Send())
                {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
                else
                {
                    echo 'Request Sent for approval';
                }
            }else{
                for($i = 0; $i < count($errors); $i++){
                    echo $errors[$i];
                }   
            }
        }else{
            for($i = 0; $i < count($errors); $i++){
                echo $errors[$i];
            }
        }
    }else{
        echo "Fill the fields";
    }
}

if(isset($_POST['Request'])){
    $Dest =             $_POST['Destination'];
    $Desc =             $_POST['Description'];
    $username =         $_SESSION['consultor']['FirstName']. " " . $_SESSION['consultor']['LastName']."<br>".$_SESSION['consultor']['Email'];
    $mail =             new PHPMailer();
    $mail->             IsSMTP();
    $mail->Host =       "smtp.office365.com"; // Your SMTP PArameter
    $mail->Port =       587; // Your Outgoing Port
    $mail->SMTPAuth =   true; // This Must Be True
    $mail->Username =   "betracking@bepcinc.com"; // Your Email Address
    $mail->Password =   "Bepcinc1"; // Your Password
    $mail->SMTPSecure = 'tls'; // Check Your Server's Connections for TLS or SSL
    $mail->From =       "betracking@bepcinc.com";
    $mail->FromName =   "Be Tracking";
    $mail->addAddress("daniel.rod.vega@hotmail.com");
    $mail->AddEmbeddedImage('../bee-logo1.png', 'logo1');
    $mail->AddEmbeddedImage('../bee logo.png', 'logo0');
    $mail->IsHTML(true);
    $mail->Subject = "Expense Request";
    $mail->Body = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
 
                <html xmlns='http://www.w3.org/1999/xhtml'>

                 <head>

                <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

                <title>Demystifying Email Design</title>

                <meta name='viewport' content='width=device-width, initial-scale=1.0'/>

                </head>
                    <body style='margin: 0; padding: 0;'>

                        <table align='center' border='1' cellpadding='0' cellspacing='0' width='600' style='border-collapse: collapse;'>
                            <tr>
                                <td bgcolor='#254366' height='100px' style='color: white; text-align: center; font-size: 50px;'>
                                    Expense Request Email
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor='#ffffff'>

                                    <table cellpadding='20' cellspacing='0' width='600' style='border-collapse: collapse;'> 
                                        <tr>
                                            <td height='80px' colspan='2'>
                                                <img src='cid:logo1' alt='' width='420' height='60' style='margin-left: 10%; display: block;'/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='height: 130px; width: 100px;'>
                                                <img src='cid:logo0' width='50' height='78'></img>
                                            </td>
                                            <td>
                                                $username<br>
                                                $Dest<br>
                                                $Desc
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>

                                            </td>
                                            <td>

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor='#ee4c50'>
                                 Row 3
                                </td>
                            </tr>
                        </table>
                    </body>
                </html>
                   ";
    if(!$mail->Send())
    {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
    else
    {
        echo 'Request Sent for approval';
    }
}

if(isset($_POST['searchCards'])){
    
    
    $info =                 $_POST['searchCards'];
    if($_SESSION['consultor']['Type'] != '0'){
        $query =            $connection->prepare("SELECT t.*, project.Name as pName, consultors.Firstname, consultors.Lastname, COUNT(expenses.ID) AS expQty
                                                FROM travels t
                                                INNER JOIN assignment ON (t.AssignmentID = assignment.ID)
                                                INNER JOIN project ON (assignment.ProjectID = project.ID)
                                                INNER JOIN consultors ON (consultors.ID = t.ConsultorID)
                                                INNER JOIN expenses ON (expenses.TravelID = t.ID)
                                                WHERE t.ConsultorID =".$_SESSION['consultor']['Type']."
                                                AND t.Name LIKE ?
                                                AND project.Name LIKE ?
                                                AND CONCAT(consultors.Firstname,' ', consultors.Lastname) LIKE ?
                                                AND DATE(t.FromDate) = DATE(?)
                                                AND DATE(t.ToDate) = DATE(?)
                                                AND t.Status = ?
                                                GROUP BY t.ID");
    }
    else{
        $query =            $connection->prepare("SELECT t.*, project.Name as pName, consultors.Firstname, consultors.Lastname, COUNT(expenses.ID) AS expQty
                                                FROM travels t
                                                INNER JOIN assignment ON (t.AssignmentID = assignment.ID)
                                                INNER JOIN project ON (assignment.ProjectID = project.ID)
                                                INNER JOIN consultors ON (consultors.ID = t.ConsultorID)
                                                INNER JOIN expenses ON (expenses.TravelID = t.ID)
                                                WHERE t.Name LIKE ?
                                                AND project.Name LIKE ?
                                                AND CONCAT(consultors.Firstname,' ', consultors.Lastname) LIKE ?
                                                AND DATE(t.FromDate) BETWEEN DATE(?) AND DATE(?)
                                                AND DATE(t.ToDate) BETWEEN DATE(?) AND DATE(?)
                                                AND t.Status = ?
                                                GROUP BY t.ID");

    }
    $query ->               bind_param("sssssssi", $Tn, $Pn, $Cn, $Fd, $Fd_u, $Td, $Td_u, $St);
    $Tn =                   "%{$info[0]}%";
    $Pn =                   "%{$info[1]}%";
    $Cn =                   "%{$info[2]}%";
    
    if($info[3] != ""){
        $Fd =                   $info[3];
        $Fd_u =                 date('Y-m-d', strtotime($info[3]. ' + 1 days'));
    }else{
        $Fd =                   "2010-01-01"; 
        $Fd_u =                 "2100-01-01";   
    }
    
    if($info[4] != ""){
        $Td =                   $info[4];
        $Td_u =                 date('Y-m-d', strtotime($info[4]. ' + 1 days'));
    }else{
        $Td =                   "2010-01-01"; 
        $Td_u =                 "2100-01-01";       
    }
    $St =                   1;
    $query ->               execute();
    $cadena =               "";
    $resultado =            $query->get_result();
    if ($resultado->num_rows>0) {
        while($row = $resultado->fetch_assoc()) {
            if($row['Status'] == 0){
                $status = 'Submitted';
            }else if($row['Status'] == 1){
                $status =   'Approved';
            }
            $cadena = $cadena."<div class='contacto'>
                                <div class='eName' style='cursor: pointer;' onclick=\"LoadPage('Expense.php?id=".$row['ID']."');\">".$row['Name']."</div>
                                <div class='eTName'>".$row['pName']."</div>
                                <div class='eCName'>".$row['Firstname']." ".$row['Lastname']."</div>
                                <div class='eCategory'>".substr($row['FromDate'], 0, 10)."</div>
                                <div class='eSubmitD'>".substr($row['ToDate'], 0, 10)."</div>
                                <div class='eQty'>".$row['expQty']."</div>
                                <div class='eStatus'>".$status."</div>
                            </div>";
        }
        echo $cadena;
        $query -> close();
    }else{
        echo "No Results Found :(";
    }
}