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
            <script src="../Resources/Javascript/PoJS.js"></script>
            <script src="../Resources/Javascript/TableFilters.js"></script>
            <meta charset="UTF-8">
            <title>
            </title>
        </head>
        <body>
            <div class="wrapper">
                <div>
                    <h1>POs</h1>
                </div>
                <div class="button" align="center">
                    <input type="button" id="boton" value="ADD PO">
                </div>
            </div>
            <!-- -->
            <div class="sidebar-contact">
                <div class="contenedor">
                    <h2>NEW PO</h2>
                    <hr id='line'>
            <form id="PO" class="masterForm" onsubmit='return RevisarInfo();'>
                <div class="Linea">
                    <div class="plaecHolder">
                        PO Number
                    </div>
                    <div class="entrada">
                        <input type='text' class='unico' id='Number' required>
                    </div>
                </div>
                <div class="Linea">
                    <div class="plaecHolder">
                        Ammount
                    </div>
                    <div class="entrada">
                        <input type='number' class='unico' id='Ammount' required style="width: 50%;" step="0.01">
                    </div>
                </div>
                <div class="Linea">
                    <div class="plaecHolder">
                        Currency
                    </div>
                    <div class="entrada">
                        <select id='currency' class='unico'>
                            <option value='0'>MXN</option>
                            <option value='1'>USD</option>
                        </select>
                    </div>
                </div>
                <div class="Linea">
                    <div class="plaecHolder">
                        Status
                    </div>
                    <div class="entrada">
                        <select id='status' class='unico'>
                            <option value='0'>INACTIVE</option>
                            <option value='1'>ACTIVE</option>
                            <option value='2'>TEMPORAL</option>
                        </select>
                    </div>
                </div>
            </form>
                </div>
                <div class="cont-boton">
                    <input type="submit" name="" value="ADD" id='submittir' form="PO">
                    <input type="submit" name="" value="Add another" id='submittir'>
                    <input type="button" id="cancel-boton" value="cancel">
                </div>
            </div>
            <!-- -->
            
            <div class="box">
                <div class="signup-box"> 
                    <div class="infoTable" id="tabla">
                        <div class="contacto" style='height:    30px;'>
                            <form class='searcFilters' id='SearchTable' onsubmit="return searchCards();">
                                <div class="number">&nbsp;</div>
                                <div class="poNumber searchParam">
                                    <input type="text" placeholder="PO" class='searchFilters' id="resourceIDSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT NoPO FROM po");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class="poProject searchParam">
                                <input type="text" placeholder="Project" class='searchFilters' id="resourceIDSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT p.Name 
                                                                                    FROM project p 
                                                                                    WHERE p.ID IN (SELECT ProjectID FROM assignment WHERE PO!=0)");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class="poAmmount searchParam">
                                    <select id="statusSearch" class="searchParam" class='searchFilters'>
                                        <option value="All">-- ALL --</option>
                                        <option value="0">MXN</option>
                                        <option value="1">USD</option>
                                    </select>
                                </div>
                                <div class="poCurrency">
                                    <select id="statusSearch" class="searchParam" class='searchFilters'>
                                        <option value="All">-- ALL --</option>
                                        <option value="1">ACTIVE</option>
                                        <option value="0">INACTIVE</option>
                                        <option value="2">TEMPORAL</option>
                                    </select>
                                </div>
                                <div class="poStatus">
                                    <input type="submit" id="searchParameters" value="Search">
                                </div>
                            </form>
                        </div>
                        <div class="contacto">
                            <div class="number">&nbsp;</div>
                            <div class="poNumber">PO Number</div>
                            <div class="poProject">Project</div>
                            <div class="poAmmount">Ammount</div>
                            <div class="poCurrency">Currency</div>
                            <div class="poStatus">Status/Type</div>
                        </div>
                        <?php
                            $query =            $connection->query("SELECT p.*
                                                                    FROM po p");
                            while($row = $query->fetch_array()){
                                $querty =           $connection->query("SELECT po.*, a.Name, project.Name as pName
                                                                        FROM assignment a
                                                                        INNER JOIN po ON (a.PO = po.ID)
                                                                        INNER JOIN project ON (a.ProjectID = project.ID)
                                                                        WHERE po.ID=".$row['ID']."");
                                $querty =           $querty->fetch_object();
                                if($querty === null){
                                    $pName =    "No Project Assigned";
                                }else{
                                    $pName =    $querty->pName;    
                                }
                                if($row['Currency'] == '0')
                                    $currency =       "MXN";
                                else
                                    $currency =       "USD";

                                if($row['Status'] == '0')
                                    $status =         "Inactive";
                                else if($row['Status'] == '1')
                                    $status =         "Active";
                                else
                                    $status =         "Temporal";
                                echo"
                                    <div class='contacto'>
                                        <div class='number'>".$row['ID']."</div>
                                        <div class='poNumber' style='cursor: pointer;' onclick=\"LoadPage('Administrators/PO.php?id=".$row['ID']."');\" >".$row['NoPO']."</div>
                                        <div class='poProject'>".$pName."</div>
                                        <div class='poAmmount'>$".$row['Ammount']."</div>
                                        <div class='poCurrency'>".$currency."</div>
                                        <div class='poStatus'>$status</div>
                                    </div>
                                ";
                                //echo "<div class='contactoInfo'></div>";
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
