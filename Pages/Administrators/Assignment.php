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
include('../../Resources/WebResponses/connection.php');
include('../../Resources/InfoFill/AssignmentFill.php');
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
                <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Listas_General.css">
                <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Pages/Contact_Layout.css">
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
                <script src="../Resources/Javascript/ContactsJS.js"></script><meta charset="UTF-8">
                <title>
                </title>
            </head>
            <body>
                <div id="contenedor">
                    <div id="resumen">
                        <div id="arriba">
                            <div id="badge">
                                &nbsp;<i class="fas fa-address-card"></i>
                            </div>
                            <div id="presentacion">
                                Assignment
                                <br>
                                <?php
                                    echo $c['Name'];
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
                                    D
                                </div>
                                <div class="dato">
                                    Account Name
                                </div>
                                <div class="dato" style="width: 12% !important;">
                                    <?php echo $ID." " . $c['Name']; ?>
                                </div>
                                <div class="dato">
                                    <?php echo $c['Name']; ?>
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
                            </div>
                            <div id="advertenquia">

                            </div>
                            <!-- -->
                              <?php
                                  DisplayTimecards($connection, $ID);
                              ?>
                            <!-- -->
                              <?php
                                  $respuesta = DisplayProjects($connection, $ID);
                                  echo $respuesta[0];
                              ?>
                            <!-- -->
                              <?php
                                  DisplayAssignments($connection, $ID, $respuesta[1]);
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
