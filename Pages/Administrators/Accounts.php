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
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
?>
    <html>
        <head>
            
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Listas_Layout.css">
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/ListasPrincipales_Layout.css">
            <script src="../Resources/Javascript/ContactsJS.js"></script>
            <script src="../Resources/Javascript/menu.js"></script>
            <script src="../Resources/Javascript/AccountJS.js"></script>
            <title>
            </title>
        </head>
        <body>
            <div class="wrapper">
                <div>
                    <h1>Accounts</h1>
                </div>
                <div class="button" align="center">
                    <input type="button" id="boton" value="ADD Account">
                </div>
            </div>
            
            <!-- -->
            <div class="sidebar-contact">
                <div class="contenedor">
                    <h2>NEW ACCOUNT</h2>
                    <hr id='line'>
                    <form id="Assignment" class='masterForm' onsubmit='return RevisarInfo();'>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Name
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='Name' required>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Phone
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='Phone' required>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Address
                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='Address' required>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Division
                            </div>
                            <div class="entrada">
                                <select id="Division">
                                    <?php
                                        $queryDiv =             $connection->query("SELECT * FROM divisions");
                                        while($row = $queryDiv->fetch_array()){
                                            echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Industry
                            </div>
                            <div class="entrada">
                                <select id="Division">
                                    <?php
                                        $queryInd =             $connection->query("SELECT * FROM industries");
                                        while($row = $queryInd->fetch_array()){
                                            echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">
                                Manager
                            </div>
                            <div class="entrada">
                                <select id="Division">
                                    <?php
                                        $queryInd =             $connection->query("SELECT ID, Name FROM account_manager");
                                        while($row = $queryInd->fetch_array()){
                                            echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                        }
                                    ?>
                                </select>
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
            <!-- -->
            <div class="box">
                <div class="signup-box"> 
                    <div class="infoTable">
                        <div class="contacto">
                            <div class="number">&nbsp;</div>
                            <div class="accName">Name</div>
                            <div class="accIndustry">Industry</div>
                            <div class="accAddress">Address</div>
                            <div class="accCompany">Company</div>
                            <div class="accManager">Manager</div>
                        </div>
                        <?php
                            $query =            $connection->query("SELECT a.*, industries.Name as iName, divisions.Name as dName, account_manager.Name as aName
                                                                    FROM account a
                                                                    INNER JOIN industries ON (a.Industry = industries.ID)
                                                                    INNER JOIN divisions ON (divisions.ID = a.Division)
                                                                    INNER JOIN account_manager ON (account_manager.ID = a.ManagerID)");
                            while($row = $query->fetch_array()){
                              echo"
                                  <div class='contacto'>
                                      <div class='number'>".$row['ID']."</div>
                                      <div class='accName' style='cursor: pointer' onclick=\"LoadPage('Administrators/Account.php?id=".$row['ID']."');\" >".$row['Name']."</div>
                                      <div class='accIndustry'>".$row['iName']."</div>
                                      <div class='accAddress'>".$row['Address']."</div>
                                      <div class='accCompany'>".$row['Company']."</div>
                                      <div class='accManager'>".$row['aName']."</div>
                                  </div>";
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
