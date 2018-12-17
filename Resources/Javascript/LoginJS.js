/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 *
 *
 */

function changeBackground(){
    preload();
    var src =       document.getElementById("contenedor");
    src.appendChild(images[1]);
    //setInterval(displayNextImage, 4000);
    $( "#contenedor" ).remove( ".delete" );
}
function mostrarRecuperar(){
    $("#forgot").slideToggle();
}
function EnviarPassword(){
    var mail =          document.getElementById('emailRec').value;
    alert (mail);
    return false;
}
function Login(){
    var recordar =         document.getElementById('remember').checked;
    var rem =               0;
    if(recordar == true)
        rem =               1;
    var usuario =          document.getElementById('usuario').value;
    var password =         document.getElementById('password').value;
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    'Resources/WebResponses/Login.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {usuario: usuario, contra: password, rem: rem}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            //alert (data);
            if(data != "Wrong Credentials"){
                window.location.href = "Pages/Principal.php";
            }else{
                alert (data);
            }
        }
    });
    return false;
}

function StraightLogin(){
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    'Resources/WebResponses/Login.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {Straight: '1'}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            if(data != "Wrong Credentials"){
                window.location.href = "Pages/Principal.php";
            }else{
                alert (data);
            }
        }
    });
}

function ChangeAccount(){
    document.getElementById('formulario').remove();
    var formulario = "<form id='formulario' onsubmit=\"return Login();\">" +
                            "<div class='info'>&nbsp;&nbsp;Nombre de Usuario</div>" +
                            "<div class='input'><input type='text' name='username' id='usuario' required></div>" +
                            "<div class='info'>&nbsp;&nbsp;Contraseña</div>" +
                            "<div class='input'><input type='password' name='password' id='password' required></div>" +
                            "<div class='input'><input type='submit' form='formulario' value='LOGIN'></div>" +
                            "<div id='recordar'><input type='checkbox' id='remember' style='float:left; width: 15px; margin-top: -6px;'>Recordarme</div>" +
                            "<div id='recuperar' onclick='mostrarRecuperar();'>¿Olvidaste tu contraseña?</div>" +
                        "</form>";
    document.getElementById('login').insertAdjacentHTML('beforeend', formulario);
}
