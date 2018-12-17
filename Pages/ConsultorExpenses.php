<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
//echo $_SESSION['dataBase']. " ". $_SESSION['loggedin']. " ". $_SESSION['userID']. " ". $_SESSION['userName'];
$IDUsuario =            $_SESSION['consultor']["ID"];
$UserName =             $_SESSION['consultor']["SN"];
$Sponsor =              $_SESSION['consultor']["Sponsor"];
include('../Resources/WebResponses/connection.php');
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
?>
    <html>
        <head>
            <script src="../Resources/Javascript/Expenses/ConsultorExpensesJS.js"></script>
            <link rel="stylesheet" href="../Resources/CSS/Timecards/Timecards_Layout.css">
            <link href="https://fonts.googleapis.com/css?family=Montserrat|Cairo" rel="stylesheet">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
            <meta charset="UTF-8">
            <title>
            </title>
        </head>
        <body>
            <div id="contenedor">
                <div id="buscador">
                    <div id="searchParams">
                        <div>
                            <div id="image">
                                IMG
                            </div>
                            <div id="banner">
                                Timecards
                            </div>
                            <div id="search">
                                View
                                <select id="parameter">
                                    <option value="1">All</option>
                                    <option value="2">Other</option>
                                </select>
                                <button id="searchButton">Go!</button>
                            </div>
                        </div>
                    </div>
                    <div id="new">
                        <button id="botonNew" onclick="nuevoExpense();"><i class="fas fa-plus-circle"></i></button>
                    </div>
                </div>
                <div id="timecards">
                    <div id="tableInfo">
                    </div>
                    <table id="timeTable">
                        <thead>
                            <tr>
                                <th>Timecard ID</th>
                                <th>Resource</th>
                                <th>Project</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Total Days Worked</th>
                                <th>Total Hours</th>
                            </tr>
                        </thead>
                        <?php
                            $query =            $connection->query("SELECT * FROM timecards WHERE ConsultorID='".$_SESSION['consultor']['ID']."'");
                            while($row = $query->fetch_array()){
                                $id =           $row['ID'];
                                $start =        substr($row['StartingDay'], 0, 10);
                                //$end =          strtotime(str_replace("/","-", $start));
                                $end =          new DateTime($start);
                                $end ->         add(new DateInterval('P6D'));
                                $date =         $end ->format('Y-m-d');
                                $queryDatos =   $connection->query("SELECT t.*, sponsor.Name as a, assignment.Name as b
                                                                    FROM timecards t
                                                                    INNER JOIN sponsor ON (sponsor.ID = '".$_SESSION['consultor']['Sponsor']."')
                                                                    INNER JOIN assignment ON (assignment.ID = '".$_SESSION['consultor']['ID']."')");
                                $queryDatosR =  $queryDatos->fetch_object();
                                $Sponcor =      $queryDatosR->a;
                                $Acign =        $queryDatosR->b;
                                $timeID =       $queryDatosR->ID;
                                $queryNo =      $connection->query("SELECT Name FROM assignment WHERE ID = (SELECT AssignmentID FROM lineas WHERE ConsultorID='".$_SESSION['consultor']['ID']."' AND TimecardID='$id' ORDER BY ID ASC Limit 1)");
                                $queryNoR =     $queryNo->fetch_object();
                                $nombrecito =   $queryNoR->Name;
                                $hours =        0;
                                $days =         0;

                                $queryLineas =  $connection->query("SELECT AssignmentID, SUM(Mon), SUM(Tue), SUM(Wed), SUM(Thu), SUM(Fri), SUM(Sat), SUM(Sun) FROM `lineas` WHERE TimecardID='$id' GROUP BY AssignmentID");
                                while($fila = $queryLineas->fetch_array()){
                                    //if(intval($fila['AssignmentID']) >= 5 ){ ESTE
                                    if(intval($fila['AssignmentID']) < 5 ){
                                        for($j = 1 ; $j < 8; $j++){
                                            $hours += $fila[$j];
                                            if(intval($fila[$j]) !== 0){
                                                $days++;
                                            }
                                        }
                                    }
                                }
                                echo"
                                    <tr>
                                        <td>".$row['Name']."</td>
                                        <td>$Sponcor</td>
                                        <td>$nombrecito</td>
                                        <td>$start</td>
                                        <td>$date</td>
                                        <td>Approved</td>
                                        <td>$days</td>
                                        <td>$hours</td>
                                    </tr>
                               ";
                            }
                        ?>
                    </table>
                </div>
                <div id="bottom">

                </div>
            </div>
        </body>
    </html>
    <?php
}else{
    header("Location: ../index.php");
}
?>
