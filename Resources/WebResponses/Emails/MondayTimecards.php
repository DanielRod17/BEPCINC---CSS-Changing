<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include('../connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

$mail =                     new PHPMailer;
$querySponsors =            $connection->query("SELECT * FROM sponsor");
$dias =                     array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');

while($row = $querySponsors->fetch_array()){
    //$domingo =                  date('Y-m-d', strtotime('last Sunday', strtotime(date("Y-m-d"))));
    $domingo =                  "2018-12-18";
    $queryConsultors =          $connection->query("SELECT c.Firstname, c.Lastname, c.ID
                                                FROM consultors c
                                                WHERE c.ID IN (SELECT ConsultorID 
                                                               FROM assignment
                                                               WHERE ProjectID IN (SELECT ID FROM project WHERE SponsorID = '".$row['ID']."')
                                                              )");
    if($queryConsultors -> num_rows != 0){
        echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
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
                                            <td bgcolor='#254366' style='color: white; text-align: center; font-size: 15px;' height='20'>
                                                End Date
                                            </td>
                                            <td bgcolor='#254366' style='color: white; text-align: center; font-size: 15px;' height='20'>
                                                Project Name
                                            </td>
                                            <td bgcolor='#254366' style='color: white; text-align: center; font-size: 15px;' height='20'>
                                                Consultant
                                            </td>
                                            <td bgcolor='#254366' style='color: white; text-align: center; font-size: 15px;' height='20'>
                                                Timecard ID
                                            </td>
                                            <td bgcolor='#254366' style='color: white; text-align: center; font-size: 15px;' height='20'>
                                                Total Hours
                                            </td>
                                            <td bgcolor='#254366' style='color: white; text-align: center; font-size: 15px;' height='20'>
                                                Project Sponsor
                                            </td>
                                        </tr>";
                                    
        //echo $row['Email']."<br>";
        while($Cons = $queryConsultors->fetch_array()){
            //echo $Cons['Firstname']." ".$Cons['Lastname']." ".$Cons['ID']."<br>";
            $queryCards =               $connection->query("SELECT *, DATE(DATE_ADD(StartingDay, INTERVAL 6 DAY)) as Ending, assignment.Name as aName
                                                            FROM lineas
                                                            LEFT JOIN assignment ON (lineas.AssignmentID = assignment.ID)
                                                            LEFT JOIN project ON (assignment.ProjectID = project.ID)
                                                            WHERE lineas.ConsultorID='".$Cons['ID']."' 
                                                            AND DATE(DATE_ADD(StartingDay, INTERVAL 6 DAY)) = DATE('$domingo')");
            if($queryCards -> num_rows != 0){
                /////////////////////////////////////////////////
                /*$mail->IsSMTP();
                $mail->Host = "smtp.office365.com"; // Your SMTP PArameter
                $mail->Port = 587; // Your Outgoing Port
                $mail->SMTPAuth = true; // This Must Be True
                $mail->Username = "betracking@bepcinc.com"; // Your Email Address
                $mail->Password = "Bepcinc1"; // Your Password
                $mail->SMTPSecure = 'tls'; // Check Your Server's Connections for TLS or SSL

                $mail->From = "betracking@bepcinc.com";
                $mail->FromName = "Be Tracking";
                $mail->AddAddress($row['Email']);
                $mail->AddEmbeddedImage('../bee-logo1.png', 'logo1');
                $mail->AddEmbeddedImage('../bee logo.png', 'logo0');

                $mail->IsHTML(true);

                $mail->Subject = "Expense Request";
                $mail->Body = */
                /*if(!$mail->Send())
                {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                }
                else
                {
                    echo 'Email Sent';
                }*/
                while($Cards = $queryCards -> fetch_array()){
                    //$queryCards =               $connection->query("SELECT *, DATE(DATE_ADD(StartingDay, INTERVAL 6 DAY)) as Ending FROM lineas WHERE ConsultorID='".$Cons['ID']."' AND DATE(DATE_ADD(StartingDay, INTERVAL 6 DAY)) = DATE('$domingo')");
                    $hours =                    0;
                    foreach($dias as $dia){
                        $hours +=   $Cards[$dia];
                    }
                    echo "<tr>
                        <td>".$Cards['Ending']."</td><td>".$Cards['aName']."</td><td>".$Cons['Firstname']." ".$Cons['Lastname']."</td><td>".$Cards['TimecardID']."</td><td>"."$hours</td><td>Sponsor".$row['Name']."</td><tr>";
                }
                /////////////////////////////////////////
            }else{
                //echo "No timecards ";
            }
            echo "<br>";
        }
        echo "</table>
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
    }else{
        //echo $row['Email']." Doesn't have any consultors";
    }
}