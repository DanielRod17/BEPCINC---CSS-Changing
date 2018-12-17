<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    session_start();
    if(!isset($_SESSION['consultor']['Login'])){ //If there's no login information
?>
        <html>
            <head>
                <script src="Resources/Javascript/LoginJS.js"></script>
                <link rel="stylesheet" href="Resources/CSS//Login_Layout.css">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
                <meta charset="UTF-8">
                <title></title>
            </head>
            <body>
                <div id="contenedor">
                    <div id="login">
                        <div id="logoAndInfo">
                            <div id="imagenLogo">
                                <img src="Resources/bee-logo1.png" id="beeLogo">
                            </div>
                        </div>
                        <?php
                            if(!isset($_COOKIE['remember_me'])){
                        ?>
                        <form id="formulario" onsubmit="return Login();">
                            <div class="info">&nbsp;&nbsp;Nombre de Usuario</div>
                            <div class="input"><input type="text" name="username" id="usuario" required></div>
                            <div class="info">&nbsp;&nbsp;Contraseña</div>
                            <div class="input"><input type="password" name="password" id="password" required></div>
                            <div class="input"><input type="submit" value="LOGIN"></div>
                            <div id="recordar"><input type="checkbox" id='remember' style="float:left; width: 15px; margin-top: -6px;">Recordarme</div>
                            <div id="recuperar" onclick="mostrarRecuperar();">¿Olvidaste tu contraseña?</div>
                        </form>
                        <?php
                            }else{
                                $data = unserialize($_COOKIE['remember_me']);
                        ?>
                            <form id="formulario">
                                <div class="info" style="font-size: 200%; margin-top: 10%;">&nbsp;&nbsp;<?php  echo "Welcome back ".$data['First']."!"; ?></div>
                                <div class="input" style="margin-top: 5%;">
                                    <input type="submit" value="LOGIN" form='' onclick='StraightLogin();'>
                                    <input type="submit" value="CHANGE ACCOUNT" form='' onclick='ChangeAccount();'>
                                </div>
                            </form>
                        <?php
                            }
                        ?>
                    </div>
                    <div id="information">
                        <div class="hexagon">
                            <p>
                                En el Cuadrante Mágico de Gartner, se nombró a Salesforce como
                                líder debido al centro de interacción con el cliente de CRM por 10 años consecutivos
                                <br>
                            </p>
                                <button id="conoceMas">CONOCE MÁS</button>
                        </div>
                    </div>
                    <div id="forgot">
                        <form id="enviarRecuperar" onsubmit="return EnviarPassword();">
                            <div class="info"><i class="far fa-envelope"></i>   &nbsp;&nbsp;E-MAIL</div>
                            <div class="input"><input type="text" name="emailRec" id="emailRec" required></div>
                            <div class="input"><input type="submit" value="SEND"></div>
                        </form>
                    </div>
                </div>
            </body>
        </html>
    <?php
    }else{ //If a user is loged in, redirect to the main page
        header("Location: Pages/Principal.php");
    }
    ?>
