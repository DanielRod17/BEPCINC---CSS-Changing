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
include('../../Resources/WebResponses/connection.php');
unset($_SESSION['fecha']);
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
    $queryDel =         $connection->query("DELETE FROM lineas WHERE ConsultorID = '".$_SESSION['consultor']['ID']."' AND TimecardID='1'");
?>
    <html>
        <head>
            <script src="../Resources/Javascript/Timecards/TimecardsJS.js"></script>
            <link rel="stylesheet" href="../Resources/CSS/Timecards/Timecards_Layout.css">
            <script>
                $( function() {
                    $( "#datepicker" ).datepicker({
                        altFormat: 'yyyy-mm-dd',  // Date Format used
                        firstDay: 0, // Start with Monday
                        beforeShowDay: function(date) {
                            return [date.getDay() === 0,''];
                        }
                    });
                });
            </script>
            <meta charset="UTF-8">

            <title>
            </title>
        </head>
        <body>
            <div id='modal'>
                <div id='modalContent'  class="modalesCon">
                    <div class='banner'>My Assignments</div>
                    <div class='projectos'>
                        <?php
                            $userID =           $_SESSION['consultor']["ID"];
                            //$query =            $connection->query("SELECT Name FROM assignment WHERE ID = (SELECT Assignment FROM consultors WHERE ID='$userID')");
                            //
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
            </div>
            <div id="contenedor">
                <div class="titulo">Timecards</div>
                <div id ="alertas"></div>
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
                </div>
                <div id="timecards">
                    <div id="tableInfo">
                        <button onclick="weekChange('0');"><<</button><input type="text" id="datepicker" onchange="actualizarTabla(this);" autocomplete="off"><button onclick="weekChange('1');">>></button>
                        <input type='submit' form='timeForms' value='Save'>
                        <button onclick="Approve();" disabled id="approve">Submit</button>
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
                            <?php
                                echo "<form id='timeForms' onsubmit='return guardarTimecard();'>";
                                for($i = 1; $i <= 5; $i++){
                                    echo"
                                    <tr class='DaysInput $i'>
                                        <td class='updateProj'>
                                            <i class='icon fas fa-search' onclick=\"DisplayProjects('$i');\" ></i>
                                            <input type='text' class='project $i'></td>
                                        <td class='updateDay'><input type='number' class ='hourDay' min='0' max='24'></td>
                                        <td class='updateDay'><input type='number' class ='hourDay' min='0' max='24'></td>
                                        <td class='updateDay'><input type='number' class ='hourDay' min='0' max='24'></td>
                                        <td class='updateDay'><input type='number' class ='hourDay' min='0' max='24'></td>
                                        <td class='updateDay'><input type='number' class ='hourDay' min='0' max='24'></td>
                                        <td class='updateDay'><input type='number' class ='hourDay' min='0' max='24'></td>
                                        <td class='updateDay'><input type='number' class ='hourDay' min='0' max='24'></td>
                                        <td class='sum'></td>
                                        <td></td>
                                    </tr>";
                                }
                                echo "</form>";
                            ?>
                        </thead>
                    </table>
                </div>
            </div>
        </body>
    </html>
    <?php
}else{
    header("Location: ../index.php");
}
?>
