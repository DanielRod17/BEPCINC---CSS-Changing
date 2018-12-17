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
include('../Resources/WebResponses/connection.php');
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
?>
    <html>
        <head>
            
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Listas_Layout.css">
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/ListasPrincipales_Layout.css">
            <script src="../Resources/Javascript/TableFilters.js"></script>
            <script>
                $( function() {
                  $( "#SDate, #EDate" ).datepicker();
                } );
            </script>
            <meta charset="UTF-8">
            <title>
            </title>
        </head>
        <body>
            <div class="wrapper">
                <div>
                    <h1>Assignments</h1>
                </div>
                    <?php
                        if($_SESSION['consultor']['Type'] == '0'){
                    ?>
                        <div class="button" align="center">
                            <input type="button" id="boton" value="ADD Assignment">
                        </div>
                    <?php
                        }
                    ?>
            </div>
            <!-- -->
            <?php
                if($_SESSION['consultor']['Type'] == '0'){
            ?>
            <div class="sidebar-contact">
                <div class="contenedor">
                    <h2>NEW ASSIGNMENT</h2>
                    <hr id='line'>
                    <form id="Assignment" class='masterForm' onsubmit='return RevisarInfo();'>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Project
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='Project' required>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder2" style="float: left; width: 50%;">
                                BR
                            </div>
                            <div class="plaecHolder2" style="float: left; margin-left: 10%; width: 30%;">
                                Currency
                            </div>
                            <div class="entrada2">
                                <input type='number' class='unico' id='BR' required style="width: 50%;" step="0.01">
                                <select id='currency' class='unico'  style="width: 30%; margin-left: 10%;">
                                    <option value='0'>MXN</option>
                                    <option value='1'>USD</option>
                                </select>
                            </div>
                            <div class="plaecHolder2">
                                PR
                            </div>
                            <div class="entrada2">
                                <input type='number' class='unico' id='PR' required style="width: 50%;" step="0.01">
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                PO
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='PO' required style="width: 50%;" step="0.01">
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Consultor
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='Employee' required style="width: 50%;">
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Start Date
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='SDate' required style="width: 50%;">
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                End Date
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='EDate' required style="width: 50%;">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="cont-boton">
                    <input type="submit" name="" value="ADD" id='submittir' form="Assignment">
                    <input type="submit" name="" value="Add another" id='submittir'>
                    <input type="button" id="cancel-boton" value="cancel">
                </div>
            </div>
            <?php
                }
            ?>
            <!-- -->
            <div class="box">
                <div class="signup-box"> 
                    <div class="infoTable" id="tabla">
                        <div class="contacto" style='height:    30px;'>
                            <form class='searcFilters' id='SearchTable' onsubmit="return searchCards();">
                                <div class='aName searchParam'>
                                    <input type="text" placeholder="Name" class='searchFilters' id="assignmentSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Name FROM assignment WHERE ID>4");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class='aProj searchParam'>
                                    <input type="text" placeholder="Project" class='searchFilters' id="projectSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Name FROM project");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class='aCons searchParam'>
                                    <input type="text" placeholder="Consultor" class='searchFilters' id="consultorSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Firstname, LastName FROM consultors");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]." ".$row[1]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class='aBR searchParam'>
                                    <input type="text" placeholder="PO" class='searchFilters' id="poSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT NoPO FROM po");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class='aPR searchParam'>

                                </div>
                                <div class='aPO searchParam'>
                                    <input type="submit" id="searchParameters" value="Search">
                                </div>
                            </form>
                        </div>
                        <div class="contacto">
                            <div class='aName'>Name</div>
                            <div class='aProj'>Project</div>
                            <div class='aCons'>Consultor</div>
                            <div class='aBR'>BR</div>
                            <div class='aPR'>PR</div>
                            <div class='aPO'>PO</div>
                        </div>
                        <?php
                            if($_SESSION['consultor']['Type'] != '0'){
                                $query =            $connection->query("SELECT a.*, consultors.Firstname, consultors.Lastname, project.Name as pName, po.NoPO
                                                                        FROM assignment a
                                                                        INNER JOIN consultors ON(a.ConsultorID = consultors.ID)
                                                                        INNER JOIN project ON (a.ProjectID = project.ID)
                                                                        INNER JOIN po ON (a.PO = po.ID)
                                                                        WHERE a.ID > 4 AND a.ConsultorID='".$_SESSION['consultor']['ID']."'");
                            }
                            else{
                                $query =            $connection->query("SELECT a.*, consultors.Firstname, consultors.Lastname, project.Name as pName, po.NoPO
                                                                        FROM assignment a
                                                                        INNER JOIN consultors ON(a.ConsultorID = consultors.ID)
                                                                        INNER JOIN project ON (a.ProjectID = project.ID)
                                                                        INNER JOIN po ON (a.PO = po.ID)
                                                                        WHERE a.ID > 4");

                            }
                            while($row = $query->fetch_array()){
                                echo"
                                    <div class='contacto'>
                                        <div class='aName' style='cursor: pointer;' onclick=\"LoadPage('Administrators/Assignment.php?id=".$row['ID']."');\">".$row['Name']."</div>
                                        <div class='aProj'>".$row['pName']."</div>
                                        <div class='aCons'>".$row['Firstname']." ".$row['Lastname']."</div>
                                        <div class='aBR'>".$row['BR']."</div>
                                        <div class='aPR'>".$row['PR']."</div>
                                        <div class='aPO'>".$row['NoPO']."</div>
                                    </div>
                               ";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </body>
            <script src="../Resources/Javascript/ContactsJS.js"></script>
            <script src="../Resources/Javascript/menu.js"></script>
            <script src="../Resources/Javascript/AssignmentJS.js"></script>
    </html>
    <?php
}else{
    header("Location: ../index.php");
}
?>
