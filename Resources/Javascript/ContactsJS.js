/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var row = "";
$(document).ready(function()
{
    /*$(".ctdName").click(function(){
        var Nombre = $(this).attr('id');
        alert(Nombre);
        $.ajax({ //PERFORM AN AJAX CALL
            type:                   'post',
            url:                    '../Resources/WebResponses/ContactsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
            data:                   {newAssignment: '1', informacion: info}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
            success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
                //DisplayError(data);
                $(this).parent().next(".contactoInfo").slideToggle(300);
            }
        });
    });*/
    //window.parent.$("body").animate({scrollTop:0}, 'fast');
    /*var w =         "AddTimecard.php";
    var frame = $('#load', window.parent.document);
    frame.fadeOut(500, function () {
        frame.attr('src', w);
        frame.fadeIn(500);
    });*/
    $(".ctdName").click(function(){
        var frame = $("#load");
        var Nombre =    $(this).attr('id');
        var w =         "Administrators/Contact.php?id="+Nombre;
        frame.fadeOut(500, function () {
            $("#load").load(w);
            frame.fadeIn(500);
        });
        /*var Nombre =    $(this).attr('id');
        var w =         "Administrators/Contact.php?id="+Nombre;
        var frame = $('#load', window.parent.document);
        frame.fadeOut(300, function () {
            frame.attr('src', w);
            frame.fadeIn(300);
        });
        */
    });
});


function DisplayProjects(e){
    row =                           e;
    document.getElementById("modalContent").style.display =   'inline-block';
    var modales =                   document.getElementById("modal");
    modales.style.pointerEvents =   "auto";
    modales.style.display =         'inline-block';
    modales.className =             'w3-animate-show';
}

function hideProjects(){
    document.getElementById("modalContent").style.display =   'none';
    var modales =                   document.getElementById("modal");
    modales.style.pointerEvents =   "none";
    modales.style.display =         'none';
    modales.className =             'w3-animate-hide';
}

function DisplayError(e){
    var alertas = document.getElementById("alertas");
    alertas.innerHTML = "";
    alertas.innerHTML = e;
    setTimeout(() => {
        alertas.style.opacity = 1;
    }, 0);

    setTimeout(() => {
        alertas.style.opacity = 0;
    }, 3000);
}

function Displayear(e){
    var displays =                  document.getElementsByClassName("cont");
    var warning =                   document.getElementById('advertenquia');
    var details =                   document.getElementById('details');
    if(e == 1){ //
        for(var i = 0; i < displays.length ; i++){
            displays[i].style.display =       'none';
            warning.style.display =           'none';
        }
        details.style.display =               'inline-block';
    }
    else{
      for(var i = 0; i < displays.length ; i++){
          displays[i].style.display =       'inline-block';
          warning.style.display =           'inline-block';
      }
      details.style.display =               'none';
    }
}
