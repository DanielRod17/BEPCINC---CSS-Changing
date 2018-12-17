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
include('../Resources/InfoFill/ExpenseFill.php');
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
                <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Pages/Expense_Layout.css">
                <link href="https://fonts.googleapis.com/css?family=Montserrat|Cairo" rel="stylesheet">
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
                                Expense
                                <br>
                                <?php
                                    echo $c['Firstname']." ".$c['Lastname'];
                                ?>
                            </div>
                        </div>
                        <div id="abajo">
                            <div id="titulos">
                                <div class="dato">
                                    Name
                                </div>
                                <div class="dato">
                                    Travel
                                </div>
                                <div class="dato">
                                    Consultor
                                </div>
                                <div class="dato">
                                    From
                                </div>
                                <div class="dato">
                                    To
                                </div>
                            </div>
                            <div id="datos">
                                <div class="dato">
                                    <?php echo $c['Name']; ?>
                                </div>
                                <div class="dato">
                                    <?php echo $c['pName']; ?>
                                </div>
                                <div class="dato">
                                    <?php echo $c['Firstname']." ".$c['Lastname']; ?>
                                </div>
                                <div class="dato">
                                    <?php echo substr($c['FromDate'], 0, 10); ?>
                                </div>
                                <div class="dato">
                                    <?php echo substr($c['ToDate'], 0, 10); ?>
                                </div>
                            </div>
                        </div>
                        <iframe id="preview" width="200" height="200"  frameborder="0">
                        </iframe>
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
                                  DisplayFiles($connection, $ID);
                              ?>
                            <!-- -->
                              <?php
                                  DisplayExpenses($connection, $ID);
                              ?>
                            <!-- -->
                            <?php
                                  echo $detailsResult[0];
                             ?>
                        </div>
                    </div>
                </div>
                <div id="modal" onclick="vanish(this);">
                    <img id="imgPreview" src="../Resources/tinygif.gif" style="width: 50%; height: 50%; margin-left: 25%; margin-top: 15%; position: fixed; border-style: solid; border-width: 1px; border-color: white;"/>
                    <iframe id="docPreview" src="" style="width: 50%; height: 50%; margin-left: 25%; margin-top: 15%; position: fixed; border-style: solid; border-width: 1px; border-color: white;"/>
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
