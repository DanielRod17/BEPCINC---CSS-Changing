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
            <script src="../Resources/Javascript/menu.js"></script>
            <script src="../Resources/Javascript/Project/ProjectJS.js"></script>
            <script src="../Resources/Javascript/TableFilters.js"></script>
            <meta charset="UTF-8">
            <script>
                $( function() {
                  $( "#SDate, #EDate" ).datepicker();
                } );
            </script>
            <title>
            </title>
        </head>
        <body>
            <div class="wrapper">
                <div>
                    <h1>Projects</h1>
                </div>
                <?php
                    if($_SESSION['consultor']['Type'] == '0'){
                ?>
                <div class="button" align="center">
                    <input type="button" id="boton" value="ADD Project">
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
                    <h2>NEW PROJECT</h2>
                    <hr id='line'>
                    <form id="Project" class="masterForm" onsubmit='return RevisarInfo();'>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Project's Name
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='Name' required>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Sponsor
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='Sponsor' required style="width: 50%;" step="0.01">
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Project Leader
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='Leader' required style="width: 50%;" >
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Start Date
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='SDate' required style="width: 50%;" step="0.01">
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                End Date
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='EDate' required style="width: 50%;" step="0.01">
                            </div>
                        </div>
                    </form>
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
            <!-- -->
            <div class="box">
                <div class="signup-box"> 
                    <div class="infoTable" id="tabla">
                        <div class="contacto" style='height:    30px;'>
                            <form class='searcFilters' id='SearchTable' onsubmit="return searchCards();">
                                <div class="number"></div>
                                <div class="Name searchParam">
                                    <input type="text" placeholder="Name" class='searchFilters' id="resourceIDSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Name FROM project");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class="Sponsor searchParam">
                                    <input type="text" placeholder="Sponsor" class='searchFilters' id="sponsorSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Name FROM sponsor");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class="Pleader searchParam">
                                    <input type="text" placeholder="Leader" class='searchFilters' id="pLeaderSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT PLeader FROM project");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class="Company searchParam">
                                    <input type="text" placeholder="Company" class='searchFilters' id="accountManSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Name FROM subaccount");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <input type="submit" id="searchParameters" value="Search">
                            </form>
                        </div>
                        <div class="contacto">
                            <div class="number">&nbsp;</div>
                            <div class="Name">NAME</div>
                            <div class="Sponsor">SPONSOR</div>
                            <div class="Pleader">P. LEADER</div>
                            <div class="Company">COMPANY</div>
                        </div>
                        <?php
                            if($_SESSION['consultor']['Type'] == '0'){
                                $query =            $connection->query("SELECT p.*, sponsor.Name as SName, subaccount.Name as sName
                                                                FROM project p
                                                                INNER JOIN sponsor ON( p.SponsorID = sponsor.ID)
                                                                INNER JOIN subaccount ON (subaccount.ManagerID = sponsor.ManagerID)
                                                                WHERE Status='1'");
                            }else{
                                $query =            $connection->query("SELECT p.*, sponsor.Name as SName, subaccount.Name as sName
                                                                FROM project p
                                                                INNER JOIN sponsor ON( p.SponsorID = sponsor.ID)
                                                                INNER JOIN subaccount ON (subaccount.ManagerID = sponsor.ManagerID)
                                                                WHERE Status='1'
                                                                AND p.ID IN (SELECT ProjectID FROM assignment WHERE ConsultorID=".$_SESSION['consultor']['Type'].")");
                            }
                            while($row = $query->fetch_array()){
                                echo"
                                    <div class='contacto'>
                                        <div class='number'>".$row['ID']."</div>
                                        <div class='Name projName' id='".$row['ID']."' onclick='LoadPage(\"Project.php?id=".$row['ID']."\")'; >".$row['Name']."</div>
                                        <div class='Sponsor'>".$row['SName']."</div>
                                        <div class='Pleader'>".$row['PLeader']."</div>
                                        <div class='Company'>".$row['sName']."</div>
                                    </div>
                                ";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </body>
    </html>
    <?php
}else{
    header("Location: ../index.php");
}
?>
