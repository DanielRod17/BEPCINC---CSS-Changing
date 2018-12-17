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
$resultado =            array();
include('../Resources/WebResponses/connection.php');
include('../Resources/InfoFill/TimecardFill.php');
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
    if(isset($_GET['id'])){
    $ID =                   $_GET['id']; //Reemplazar por el get
    //$ID =                   2;

    $detailsResult =        DisplayDetails($connection, $ID);
    $c =                    $detailsResult[1];
    ////
    ////////////////////////////
    ////////////////////////////////
?>
        <html>
            <head>
                <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Listas_Layout.css">
                <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Listas_General.css">
                <link rel="stylesheet" href="../Resources/CSS/Timecards_Layout.css">
                <link rel="stylesheet" href="../Resources/CSS/Timecards/Timecards_Layout.css">
                <script src="../Resources/Javascript/menu.js"></script>
                <?php
                    if($_SESSION['consultor']['Type'] == '0'){
                ?>
                        <script src="../Resources/Javascript/Timecards/AdminCardsJS.js"></script>
                <?php
                    }else{
                ?>
                        <script src="../Resources/Javascript/Timecards/TimecardsJS.js"></script>
                <?php
                    }
                ?>
                <meta charset="UTF-8">
                <title>
                </title>
            </head>
            <body>
                <?php
                    if($_SESSION['consultor']['Type'] == '0' || ($_SESSION['consultor']['Type'] != '0' && $c['Submitted'] == '0')){
                ?>
                <!-- -->
                <div id='modal'>
                    <div id='modalContent'  class="modalesCon">
                        <div class='banner'>My Assignments</div>
                        <div class='projectos'>
                            <?php
                                // This section selects the assignments from the consultor
                            $query =            $connection->query("SELECT Name FROM assignment WHERE ConsultorID='".$c['ConsultorID']."'");        
                            if($query -> num_rows > 0){
                                    while($row = $query -> fetch_array()){
                                        echo "<div style='cursor: pointer;' onclick=\"AssignName(this);\" >".$row['Name']."</div>";
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
                                        echo "<div style='cursor: pointer;' onclick=\"AssignName(this);\" >".$row['Name']."</div>";
                                    }
                                }else{
                                    echo "No Projects Assigned";
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <!-- -->
                <div class="sidebar-contact" style="width: 90%; margin-left: -90%;">
                <div class="contenedor">
                    <h2>EDIT TIMECARD</h2>
                    <hr id='line'>
                    <div id="timecards">
                    <div id="tableInfo">
                        <button style='float: left; height:  30px;' onclick="weekChange('0');"><<</button><input type="text" id="datepicker" onchange="actualizarTabla(this);" autocomplete="off"><button  style='float: left; height:  30px;' onclick="weekChange('1');">>></button>
                        <input style='float: left; height:  30px; width: 100px; margin-top: 0px; margin-left: 15px;' type='submit' form='timeForms' value='Save'>
                        <?php if($_SESSION['consultor']['Type'] != '0' && $c['Submitted'] == '0'){ echo "<input style='float: left; height:  30px; width: 100px; margin-top: 0px; margin-left: 15px;' type='submit' form='' onclick=\"Aprobar('$ID');\" id='approve' value='Approve'>"; } ?>
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
                                echo "<form id='timeForms' onsubmit='return updateTimecard();'>";
                                for($i = 1; $i <= 5; $i++){
                                    echo"
                                    <tr class='DaysInput $i'>
                                        <td class='updateProj'>
                                            <i class='icon fas fa-search' style='margin-left: calc(30% - 90px);' onclick=\"DisplayProjects('$i');\" ></i>
                                            <input type='text' style='width: 100% !important; border: none!important; margin-bottom: 0px !important;' class='project $i'></td>
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
            <div class="cont-boton">
                <input type="submit" name="" value="ADD" id='submittir' form="Project">
                <input type="submit" name="" value="Add another" id='submittir'>
                <input type="button" id="cancel-boton" value="cancel">
            </div>
            </div>
            <?php
                }
            ?>
                <div id="contenedor">
                    <div id="resumen">
                        <div id="arriba">
                            <div id="badge">
                                &nbsp;<i class="fas fa-address-card"></i>
                            </div>
                            <div id="presentacion">
                                Contact
                                <br>
                                <?php
                                    echo $c['TimecardID'];
                                ?>
                            </div>
                        </div>
                        <div id="abajo">
                            <div id="titulos">
                                <div class="dato">
                                    Title
                                </div>
                                <div class="dato">
                                    Account Name
                                </div>
                                <div class="dato" style="width: 12% !important;">
                                    Phone
                                </div>
                                <div class="dato">
                                    Email
                                </div>
                                <div class="dato">
                                    Contact Owner
                                </div>
                            </div>
                            <div id="datos">
                                <div class="dato">
                                    <?php echo $c['Title']; ?>
                                </div>
                                <div class="dato">
                                    <?php echo $c['Firstname']." ".$c['Lastname']; ?>
                                </div>
                                <div class="dato" style="width: 12% !important;">
                                    <?php echo $c['Email']; ?>
                                </div>
                                <div class="dato">
                                    <?php echo $c['Firstname']." ".$c['Lastname']; ?>
                                </div>
                                <div class="dato">
                                    Contact Owner
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="contenido">
                        <div id="contenidoCTN">
                            <div id="opcaos">
                                <div class="opcContenido" onclick="Displayear(0);">
                                    Related
                                </div>
                                <div class="opcContenido" onclick="Displayear(1);" style='margin-left: 130px;'>
                                    Details
                                </div>
                                <?php
                                    if($_SESSION['consultor']['Type'] == '0' || ($_SESSION['consultor']['Type'] != '0' && $c['Submitted'] == '0')){
                                ?>
                                      <div class="opcContenido" id='boton' onclick="editTimecard('<?php echo $ID; ?>')"; style='margin-left: 260px;'>
                                          Edit
                                      </div>
                                <?php
                                    }
                                ?>
                            </div>
                            <div id="advertenquia">

                            </div>
                            <!-- -->
                              <?php
                                  DisplayHistory($connection, $ID);
                              ?>
                            <!-- -->
                              <?php
                                  DisplayDetails($connection, $ID);
                              ?>
                            <!-- -->
                            <?php
                                  echo $detailsResult[0];
                             ?>
                        </div>
                    </div>
                </div>
            </body>
        </html>
        <?php
    }else{
        echo "Forbidden Access";
    }
}else{
    header("Location: ../index.php");
}
?>
