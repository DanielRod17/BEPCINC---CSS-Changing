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
//if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
?>
    <html>
        <head>
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Listas_Layout.css">
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/ListasPrincipales_Layout.css">
            <script src="../Resources/Javascript/ContactsJS.js"></script><meta charset="UTF-8">
            <script src="../Resources/Javascript/menu.js"></script>
            <script src="../Resources/Javascript/AddUserJS.js"></script>
            <script src="../Resources/Javascript/TableFilters.js"></script>
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
                    <h1>Contacts</h1>
                </div>
                <div class="button" align="center">
                    <input type="button" id="boton" value="ADD Contact">
                </div>
            </div>
            <div class="sidebar-contact">
                <div class="contenedor">
                    <h2>NEW CONTACT</h2>
                    <hr id='line'>
               <!-- <div id="newCustomer">-->
                    <form id='AddContact' onsubmit='return RevisarInfo();'>
                        <h1>Contact Information</h1>
                        <div class='generalForm'>
                            <div class="Linea">
                                <div class="plaecHolder">
                                </div>
                                <div class="entrada">
                                    <input type='text' class='unico' id='Email' placeholder='Email' required>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">

                                </div>
                                <div class="entrada">
                                    <input type='password' class='unico' id='Password' placeholder='Password' required>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">
                                </div>
                                <div class="entrada">
                                    <input type='password' class='unico' id='CPassword' placeholder='Confirm Password' required>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">

                                </div>
                                <div class="entrada">
                                    <input type='text' class='unico' id='First' placeholder='First Name' required>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">

                                </div>
                                <div class="entrada">
                                    <input type='text' class='unico' id='Last' placeholder='Last Name' required>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">

                                </div>
                                <div class="entrada">
                                    <input type='text' class='unico' id='Phone' placeholder='Phone' required>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">

                                </div>
                                <div class="entrada">
                                    <input type='text' class='unico' id='EPhone' placeholder='Emergency Phone' required>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">
                                    <h1>Reports To</h1>
                                </div>
                                <div class="entrada">
                                    <select class='unico' id='ReportsTo' placeholder='Reports To'>
                                        <?php
                                            $query =            $connection->query("SELECT ID, Name FROM sponsor ORDER BY Name ASC");
                                            while($row = $query->fetch_array()){
                                                echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">

                                </div>
                                <div class="entrada">
                                    <input type='text' class='unico' id='SDate' placeholder='Start Date' required>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">

                                </div>
                                <div class="entrada">
                                    <input type='text' class='unico' id='EDate' placeholder='End Date'>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">

                                </div>
                                <div class="entrada">
                                    <input type='text' class='unico' id='Title' placeholder='Title' required>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">
                                    <h1>Division</h1>
                                </div>
                                <div class="entrada">
                                    <select class='unico' id='Division' placeholder='Division'>
                                        <option value='0'>BE OCS</option>
                                        <option value='1'>BE PRO</option>
                                        <option value='2'>BEPC MEXICO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">
                                    <h1>Functional Area</h1>
                                </div>
                                <div class="entrada">
                                    <select class='unico' id='FArea' placeholder='Functional Area'>
                                        <?php
                                            $query =            $connection->query("SELECT ID, Name FROM areas ORDER BY Name ASC");
                                            while($row = $query->fetch_array()){
                                                echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="Linea">
                                <div class="plaecHolder">

                                </div>
                                <div class="entrada">
                                    <input type='text' class='unico' id='MAddress' placeholder='Mailing Address' required>
                                </div>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder2">
                                <h1>Mailing Country</h1>
                            </div>
                            <div class="entrada2">
                                <select class="unico" id='Country' onchange='EnableStates(this.value);'>
                                    <option value="142">MX</option>
                                    <option value="231">US</option>
                                </select>
                            </div>
                            <div class="plaecHolder2">
                                <h1>Mailing State</h1>
                            </div>
                            <div class="entrada2">
                                 <select class="unico" id='State' onchange='ChangeCity(this.value);'>
                                    <?php
                                        $query =  $connection->query("SELECT name, id FROM states WHERE country_id=142");
                                        while($row = $query->fetch_array()){
                                            echo "<option value='".$row['id']."'>".$row['name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder2">
                                <h1>Mailing City</h1>
                            </div>
                              <div class="entrada2">
                                <select class="unico" id='City'>
                                  <?php
                                      $query =  $connection->query("SELECT name, id FROM cities WHERE state_id='2427'");
                                      while($row = $query->fetch_array()){
                                          $name =     $row['name'];
                                          echo "<option value='".$row['id']."'>$name</option>";
                                      }
                                  ?>
                                </select>
                            </div>
                            <div class="plaecHolder2">
                                <h1>ZIP Code</h1>
                            </div>

                            <div class="entrada2">
                                 <input type='number' class='unico' id='Zip'>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">

                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='NSS' placeholder='Numero de Seguro Social' required>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder">

                            </div>
                            <div class="entrada">
                                <input type='text' class='unico' id='RFC' placeholder='RFC'required>
                            </div>
                        </div>
                        <div class="Linea">
                            <div class="plaecHolder" style="margin-bottom: 20px;">

                            </div>
                            <div class="entrada">
                                <select class='unico' style='width: 160px;' id='Type'placeholder='Type'>
                                    <option value='2'>Consultor</option>
                                    <option value='1'>Reclutador</option>
                                    <option value='0'>Administrator</option>
                                </select>
                            </div>
                        </div>
                        <div class="Linea" style="margin-top: -67px;float:right;">
                            <div class="plaecHolder" style="margin-bottom: 20px;">

                            </div>
                            <div class="entrada">
                                <select class='unico' style='width: 160px;' id='Schedule'placeholder='Schedule'>
                                    <?php
                                        $query =  $connection->query("SELECT ID, Name FROM schedules ORDER BY Name ASC");
                                        while($row = $query->fetch_array()){
                                            $name =     $row['Name'];
                                            $name =     str_replace("_", " ", $name);
                                            $id =       $row['ID'];
                                            echo "<option value='$id'>$name</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="cont-boton">
                        <input type="submit" name="" value="ADD" id='submittir' form="AddContact">
                        <input type="submit" name="" value="Add another" id='submittir'>
                        <input type="button" id="cancel-boton" value="cancel">
                    </div>
                </form>
            </div>
            <div class="box">
                <div class="signup-box"> 
                    <div class="infoTable" id="tabla">
                        <div class="contacto" style='height:    30px;'>
                            <form class='searcFilters' id='SearchTable' onsubmit="return searchCards();">
                                <div class="NameContact searchParam">
                                    <input type="text" placeholder="Name" class='searchFilters' id="nameSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Firstname, Lastname FROM consultors WHERE Type != 0");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt tc'>".$row[0]." ".$row[1]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class="Phone searchParam">
                                    <input type="text" placeholder="Phone" class='searchFilters' id="phoneSearch" onkeyup="filter(this);">
                                        <?php
                                            $queryCards =           $connection->query("SELECT Phone FROM consultors WHERE Type != 0");
                                            while($row = $queryCards->fetch_array()){
                                                echo "<div class='cardSearchOpt tc'>".$row[0]."</div>";
                                            }
                                            $queryCards ->  close();
                                        ?>
                                </div>
                                <div class="Email searchParam">
                                    <input type="text" placeholder="Mail" class='searchFilters' id="mailSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Email FROM consultors WHERE Type != 0");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt tc'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class="ContactOw searchParam">
                                    <input type="text" placeholder="Area" class='searchFilters' id="areaSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Name FROM areas");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt tc'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <input type="submit" id="searchParameters" value="Search">
                            </form>
                        </div>
                        <div class="contacto">
                            <div class="number">&nbsp;</div>
                            <div class="NameContact">NAME</div>
                            <div class="Phone">PHONE</div>
                            <div class="Email">EMAIL</div>
                            <div class="ContactOw">AREA</div>
                        </div>
                        <?php
                            $query =            $connection->query("SELECT c.ID, c.Firstname, c.Lastname, c.Phone, c.Email, areas.Name as aName
                                                                    FROM consultors c
                                                                    INNER JOIN areas ON(c.FunctionalArea = areas.ID)
                                                                    WHERE Status='1' AND Type!='0'");
                            while($row = $query->fetch_array()){
                                echo"
                                    <div class='contacto'>
                                        <div class='number'>".$row['ID']."</div>
                                        <div class='NameContact ctdName' id='".$row['ID']."' onclick=\"LoadPage('Administrators/Contact.php?id=$id');\">".$row['Firstname']." ".$row['Lastname']."</div>
                                        <div class='Phone'>".$row['Phone']."</div>
                                        <div class='Email'>".$row['Email']."</div>
                                        <div class='ContactOw'>".$row['aName']."</div>
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
/*}else{
    header("Location: ../../index.php");
}*/
?>
