<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(!isset($_SESSION))
{
    session_start();
}

include('../../Resources/WebResponses/connection.php');
$IDUsuario =            $_SESSION['consultor']["ID"];
$UserName =             $_SESSION['consultor']["SN"];
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true && $_SESSION['consultor']['Type'] == '0'){
    ?>
    <html>
        <head>
            <link rel="stylesheet" href="../Resources/CSS/MasterCSS.css">
            <link href="https://fonts.googleapis.com/css?family=Montserrat|Cairo" rel="stylesheet">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

            <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
            <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

            <script src="../Resources/Javascript/AddUser/AddUserJS.js"></script>
            <link href="../Resources/CssAuto/css/jqueryui.css" type="text/css" rel="stylesheet"/>
            <meta charset="UTF-8">
            <title>

            </title>
        </head>
        <body>
            <div id="container">
                <div class="titulo">Edit User</div>
                <div id ="alertas"></div>
                <form id="newCustomer" class="masterForm" onsubmit='return UpdateInfo();'>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Search
                        </div>
                        <div class="entrada">
                            <input style="width: 150px !important; " type="text" id="SnChange" name="SnChange" class='unico' value=""> <button form="" onclick="GetUser()">Click me</button>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Username
                        </div>
                        <div class="entrada">
                            <input type='text' class='unico' id='SN' required disabled>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Password
                        </div>
                        <div class="entrada">
                            <input type='password' class='unico' id='Password' required disabled>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Confirm Password
                        </div>
                        <div class="entrada">
                            <input type='password' class='unico' id='CPassword' required disabled>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            First Name
                        </div>
                        <div class="entrada">
                            <input type='text' class='unico' id='First' required disabled>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Last Name
                        </div>
                        <div class="entrada">
                            <input type='text' class='unico' id='Last' required disabled>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Email
                        </div>
                        <div class="entrada">
                            <input type='text' class='unico' id='Email' required disabled>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Phone
                        </div>
                        <div class="entrada">
                            <input type='text' class='unico' id='Phone' required disabled>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder2">
                            Country
                        </div>
                        <div class="plaecHolder2">
                            State
                        </div>
                        <div class="entrada2">
                            <select class="unico" id='Country' onchange='EnableStates(this.value);' disabled>
                                <option value="MX">MX</option>
                                <option value="US">US</option>
                            </select>
                        </div>
                        <div class="entrada2">
                             <select class="unico" id='State' disabled>
                                <?php
                                    $query =  $connection->query("SELECT name FROM states");
                                    while($row = $query->fetch_array()){
                                        $name =     $row['name'];
                                        echo "<option value='$name'>$name</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder2">
                            Sponsor
                        </div>
                        <div class="plaecHolder2">
                            Assignment
                        </div>
                        <div class="entrada2">
                            <select class="unico" id='Sponsor' disabled>
                                <option value="1">Sponsor</option>
                            </select>
                        </div>
                        <div class="entrada2">
                             <select class="unico" id='Assignment' disabled>
                                 <option value="1">Assignment</option>
                                <?php
                                    /*$query =  $connection->query("SELECT name FROM states");
                                    while($row = $query->fetch_array()){
                                        $name =     $row['name'];
                                        echo "<option value='$name'>$name</option>";
                                    }*/
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder" style="margin-bottom: 20px;">
                            Type
                        </div>
                        <div class="entrada">
                            <select class='unico' style='width: 160px;' id='Type' disabled>
                                <option value='1'>Consultor</option>
                                <option value='0'>Administrator</option>
                            </select>
                        </div>
                    </div>
                    <div class="Linea" style="margin-top: 30px;">
                        <div class="plaecHolder" style="margin-bottom: 20px;">
                            Schedule
                        </div>
                        <div class="entrada">
                            <select class='unico' style='width: 160px;' id='Schedule' disabled>
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
                    <div class="Linea" style="margin-bottom: 30px;">
                        <div class="plaecHolder2">
                            Status
                        </div>
                        <div class="entrada2" style="width: 100% !important;">
                            <select class="unico" style="width: 100px !important;" id='Status' disabled>
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="Linea" style="margin-bottom: 30px;">
                        <div class="entrada">
                            <input type='submit' value='Submit' id='submittir' disabled>
                        </div>
                    </div>
                </form>
            </div>
        </body>
    </html>
    <?php
}else{
    header("Location: Dashboard.php");
}
