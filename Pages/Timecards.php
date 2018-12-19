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
$Td_u =                 date('m/d/Y', strtotime('next Sunday', strtotime(date("Y-m-d"))));
unset($_SESSION['fecha']);
include('../Resources/WebResponses/connection.php'); //Include de connection file
//If the user is loged in
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
?>
    <html>
        <head>
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Listas_Layout.css">
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/ListasPrincipales_Layout.css">
            <link rel="stylesheet" href="../Resources/CSS/Timecards_Layout.css">
            <script src="../Resources/Javascript/menu.js"></script>
            <script src="../Resources/Javascript/TableFilters.js"></script>
            <?php
            if($_SESSION['consultor']['Type'] == '0'){ //If the user is an admin, include the admin javascript file
            ?>
                <script src="../Resources/Javascript/Timecards/AdminCardsJS.js"></script>
            <?php
                }else{ //Else, include the javascript file for consultors
            ?>
                <script src="../Resources/Javascript/Timecards/TimecardsJS.js"></script>
            <?php
                }
            ?>
            <script>
                $( function() {
                    $( "#datepicker" ).datepicker({
                        beforeShowDay: function(date) {
                            var day = date.getDay();
                            return [(day == 0), ''];
                        }
                    });
                });
                actualizarTabla(document.getElementById('datepicker'));
            </script>
            <meta charset="UTF-8">
            <title>
            </title>
        </head>
        <body>
            <!-- -->
            <div id='modal'>
                <div id='modalContent'  class="modalesCon">
                    <div class='banner'>My Assignments</div>
                    <div class='projectos'>
                        <?php
                            // This section selects the assignments from the consultor
                            $userID =           $_SESSION['consultor']["ID"];
                            $query =            $connection->query("SELECT Name FROM assignment WHERE ConsultorID ='$userID'");
                            if($query -> num_rows > 0){
                                while($row = $query -> fetch_array()){
                                    echo "<div class='projItem' onclick=\"AssignName(this);\" >".$row['Name']."</div>";
                                }
                            }else{
                                echo "No Projects Assigned";
                            }
                        ?>
                    </div>
                    <div class='banner'>Global Projects</div>
                    <div class='projectos'>
                        <?php
                            $userID =           $_SESSION['consultor']["ID"];
                            $query =            $connection->query("SELECT Name FROM assignment WHERE PO='0'");
                            if($query -> num_rows > 0){
                                while($row = $query -> fetch_array()){
                                    echo "<div class='projItem' onclick=\"AssignName(this);\" >".$row['Name']."</div>";
                                }
                            }else{
                                echo "No Projects Assigned";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <!-- -->
            <div class="wrapper">
                <div>
                    <h1>Timecards</h1>
                </div>
                    <?php
                        if($_SESSION['consultor']['Type'] != '0'){
                    ?>
                <div class="button" align="center">
                    <input type="button" id="boton" value="ADD Timecard">
                </div>
            </div>
            <!-- -->
            <div class="sidebar-contact" style="width: 90%; margin-left: -90%;">
                <div class="contenedor">
                    <h2>NEW TIMECARD</h2>
                    <?php echo $_SESSION['consultor']['FirstName']." ".$_SESSION['consultor']['LastName']." - ".$_SESSION['consultor']['Title'];  ?>
                    <hr id='line'>
                    <div id="timecards">
                    <div id="tableInfo">
                        <button style='float: left; height:  30px;' onclick="weekChange('0');"><<</button><input type="text" placeholder="Week Ending" id="datepicker" onchange="actualizarTabla(this);" autocomplete="off" value="<?php echo $Td_u; ?>" ><button  style='float: left; height:  30px;' onclick="weekChange('1');" id="adelante">>></button>
                        <input style='float: left; height:  30px; width: 100px; margin-top: 0px; margin-left: 15px;' id='guardar' type='submit' form='timeForms' value='Save'>
                        <input style='float: left; height:  30px; width: 100px; margin-top: 0px; margin-left: 15px;' type='submit' form="" onclick="Approve();" disabled id="approve" value="Submit">
                    </div>
                    <table id="timeTable">
                        <thead>
                            <tr>
                                <th class='updateProj'>Project/Assignment</th>
                                <th class="updateDay" id='Mon'>Mon</th>
                                <th class="updateDay" id='Tue'>Tue</th>
                                <th class="updateDay" id='Wed'>Wed</th>
                                <th class="updateDay" id='Thu'>Thu</th>
                                <th class="updateDay" id='Fri'>Fri</th>
                                <th class="updateDay" id='Sat'>Sat</th>
                                <th class="updateDay" id='Sun'>Sun</th>
                                <th>Sum</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <?php
                            echo "<form id='timeForms' onsubmit='return guardarTimecard();'>";
                            for($i = 1; $i <= 5; $i++){
                                echo"
                                <tr class='DaysInput $i'>
                                    <td class='updateProj'>
                                        <i class='icon fas fa-search' onclick=\"DisplayProjects('$i');\" ></i>
                                        <input type='text' placeholder='Select Assigment' class='project $i'></td>
                                    <td class='updateDay'><input type='number' step='0.01' class ='hourDay' min='0' max='24'></td>
                                    <td class='updateDay'><input type='number' step='0.01' class ='hourDay' min='0' max='24'></td>
                                    <td class='updateDay'><input type='number' step='0.01' class ='hourDay' min='0' max='24'></td>
                                    <td class='updateDay'><input type='number' step='0.01' class ='hourDay' min='0' max='24'></td>
                                    <td class='updateDay'><input type='number' step='0.01' class ='hourDay' min='0' max='24'></td>
                                    <td class='updateDay' style='background-color: rgb(220, 220, 220);'><input type='number' step='0.01' class ='hourDay' min='0' max='24' style='background-color: rgb(220, 220, 220);'></td>
                                    <td class='updateDay' style='background-color: rgb(220, 220, 220);'><input type='number' step='0.01' class ='hourDay' min='0' max='24' style='background-color: rgb(220, 220, 220);'></td>
                                    <td class='sum'></td>
                                    <td></td>
                                </tr>";
                            }
                            echo "</form>
                                <tr class='DaysInput'>
                                    <td class='updateProj'>
                                        Totals
                                    <td class='updateDay'>0</td>
                                    <td class='updateDay'>0</td>
                                    <td class='updateDay'>0</td>
                                    <td class='updateDay'>0</td>
                                    <td class='updateDay'>0</td>
                                    <td class='updateDay' style='background-color: rgb(220, 220, 220);'>0</td>
                                    <td class='updateDay' style='background-color: rgb(220, 220, 220);'>0</td>
                                    <td class='sum' id='totalSum'>0</td>
                                    <td></td>
                                </tr>";
                        ?>
                    </table>
                </div>
                    </div>
            <div class="cont-boton">
                <select id="addLineas" style="float: left; width: 50px; margin-right: 10px; margin-top: 20px;">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <input type="submit" name="" value="Add another" onclick="AgregarLineas();">
                <input type="button" id="cancel-boton" value="cancel" onclick='Reset();'>
            </div>
            <?php
                }
            ?>
            </div>
            
            <!-- -->
            <div class="box">
                <div class="signup-box"> 
                    <div class="infoTable" id="tabla">
                        <div class="contacto" style='height:    30px;'>
                            <form class='searcFilters' id='SearchTable' onsubmit="return searchCards();">
                                <div class='timeCard searchParam'>
                                    <input type="text" placeholder="Timecard" class='searchFilters' id="cardIDSearch" onkeyup="filter(this);">
                                        <?php
                                            if($_SESSION['consultor']['Type'] == '0'){
                                                $queryCards =           $connection->query("SELECT TimecardID FROM lineas");
                                            }
                                            else{
                                                $queryCards =           $connection->query("SELECT TimecardID FROM lineas WHERE ConsultorID='".$_SESSION['consultor']['ID']."'");
                                            }
                                            while($row = $queryCards->fetch_array()){
                                                echo "<div class='cardSearchOpt tc'>".$row[0]."</div>";
                                            }
                                            $queryCards ->  close();
                                        ?>
                                </div>
                                <?php 
                                if($_SESSION['consultor']['Type'] == '0'){
                                ?>
                                    <div class='resource searchParam'>
                                        <input type="text" placeholder="Consultor" class='searchFilters' id="resourceIDSearch" onkeyup="filter(this);">
                                            <?php
                                                $queryCards =           $connection->query("SELECT Firstname, Lastname FROM consultors");
                                                while($row = $queryCards->fetch_array()){
                                                    echo "<div class='cardSearchOpt rs'>".$row[0]." ".$row[1]."</div>";
                                                }
                                                $queryCards ->  close();
                                            ?>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class='tProj searchParam'>
                                    <input type="text" placeholder="Project" class='searchFilters' id="projectIDSearch" onkeyup="filter(this);">
                                    <?php
                                        if($_SESSION['consultor']['Type'] == '0')
                                            $queryCards =           $connection->query("SELECT Name FROM project");
                                        else
                                            $queryCards =           $connection->query("SELECT Name
                                                                                        FROM project
                                                                                        WHERE ID IN (SELECT ProjectID FROM assignment WHERE ConsultorID='".$_SESSION['consultor']['ID']."')");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt pj'>".$row[0]."</div>";
                                        }
                                        $queryProjs =               $connection->query("SELECT Name FROM assignment WHERE PO = 0");
                                        while($row = $queryProjs ->fetch_array()){
                                            echo "<div class='cardSearchOpt pj'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class='startD searchParam'>
                                    <input type="date" placeholder="From" class='searchFilters' id="start" id="projectIDSearch">
                                </div>
                                <div class='endD searchParam'>
                                    <input type="date" id="end" class='searchFilters' placeholder="To" id="projectIDSearch">
                                </div>
                                <div class='status searchParam'>
                                    <select id="statusSearch" class="searchParam" class='searchFilters'>
                                        <option value="All">-- ALL --</option>
                                        <option value="0">Saved</option>
                                        <option value="1">Submitted</option>
                                    </select>
                                </div>
                                <div class='totalDays'>
                                    <input type="submit" id="searchParameters" value="Search">
                                </div>
                            </form>
                        </div>
                        <div class="contacto">
                            <div class='timeCard'>Timecard ID</div>
                            <div class='resource'>Resource</div>
                            <div class='tProj'>Project</div>
                            <div class='startD'>Start Date</div>
                            <div class='endD'>End Date</div>
                            <div class='status'>Status</div>
                            <div class='totalDays'>Days Worked</div>
                            <div class='totalHours'>Total Hours</div>
                        </div>
                    <?php
                        if($_SESSION['consultor']['Type'] != '0'){
                            $queryDatos =       $connection->query("SELECT t.*, consultors.Firstname as firstN, consultors.Lastname as lastN,  assignment.Name as aName
                                                                    FROM lineas t
                                                                    INNER JOIN consultors ON (consultors.ID = '".$_SESSION['consultor']['ID']."')
                                                                    INNER JOIN assignment ON (t.AssignmentID = assignment.ID)
                                                                    WHERE t.ConsultorID='".$_SESSION['consultor']['ID']."'
                                                                    ORDER BY t.ID ASC");
                        }
                        else{
                            $queryDatos =       $connection->query("SELECT t.*, consultors.Firstname as firstN, consultors.Lastname as lastN,  assignment.Name as aName
                                                                    FROM lineas t
                                                                    INNER JOIN consultors ON (consultors.ID = t.ConsultorID)
                                                                    INNER JOIN assignment ON (t.AssignmentID = assignment.ID)
                                                                    ORDER BY t.ID ASC");
                        }
                        while($row = $queryDatos->fetch_array()){
                            $id =           $row['ID'];
                            $start =        substr($row['StartingDay'], 0, 10);
                            //$end =          strtotime(str_replace("/","-", $start));
                            $end =          new DateTime($start);
                            $end ->         add(new DateInterval('P6D'));
                            $date =         $end ->format('Y-m-d');
                            $Nombre =       $row['firstN']." ".$row['lastN'];
                            $timeID =       $row['ID'];
                            $status =       $row['Submitted'];
                            if($status == 0){
                                $status =       "Saved";
                            }else if($status == 1){
                                $status =       "Submitted";
                            }
                            $hours =        0;
                            $days =         0;


                            for($j = 4 ; $j < 11; $j++){
                                $hours += $row[$j];
                                if(intval($row[$j]) !== 0){
                                    $days++;
                                }
                            }
                            echo"
                                <div class='contacto'>
                                    <div class='timeCard' style='cursor: pointer;' onclick=\"LoadPage('Timecard.php?id=$id');\">".$row['TimecardID']."</div>
                                    <div class='resource'>$Nombre</div>
                                    <div class='tProj'>".$row['aName']."</div>
                                    <div class='startD'>$start</div>
                                    <div class='endD'>$date</div>
                                    <div class='status'>$status</div>
                                    <div class='totalDays'>$days</div>
                                    <div class='totalHours'>$hours</div>
                                </div>
                           ";
                        }
                    ?>
                </div>
            </div>
        </body>
    </html>
    <?php
}else{
    header("Location: ../index.php");
}
?>
